-- Requiere MySQL 8.0+
-- Crea la base de datos
CREATE DATABASE IF NOT EXISTS cities_db
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_0900_ai_ci;
USE cities_db;

-- Recomendado: modo estricto
SET sql_mode = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- =========================================================
-- CIUDADES
-- =========================================================
CREATE TABLE city (
  id            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name          VARCHAR(120) NOT NULL,
  timezone      VARCHAR(50)  NULL,   -- IANA tz (e.g., 'America/Bogota')
  extra_data    JSON         NULL,
  created_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at    DATETIME     NULL,

  -- En MySQL 8 podemos tener un índice funcional que ignore mayúsculas/minúsculas
  PRIMARY KEY (id),
  CONSTRAINT ux_city_name UNIQUE KEY ((LOWER(name)))
) ENGINE=InnoDB;

-- =========================================================
-- CICLOS (programas/eventos de varias sesiones)
-- =========================================================
CREATE TABLE cycle (
  id            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  event_id       BIGINT UNSIGNED NOT NULL,
  name          VARCHAR(160) NOT NULL,
  start_date    DATE         NOT NULL,
  end_date      DATE         NOT NULL,
  status        ENUM('draft','active','completed','cancelled') NOT NULL DEFAULT 'draft',
  extra_data    JSON         NULL,
  created_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at    DATETIME     NULL,

  PRIMARY KEY (id),
  CONSTRAINT fk_cycle_city
    FOREIGN KEY (event_id) REFERENCES city(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  CONSTRAINT chk_cycle_dates CHECK (start_date <= end_date)
) ENGINE=InnoDB;

CREATE INDEX idx_cycle_city ON cycle(event_id);
CREATE INDEX idx_cycle_status ON cycle(status);

-- =========================================================
-- SESIONES (una fila por día/horario del ciclo)
-- =========================================================
CREATE TABLE session (
  id            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  cycle_id      BIGINT UNSIGNED NOT NULL,
  starts_at     DATETIME NOT NULL,
  ends_at       DATETIME NOT NULL,
  location      VARCHAR(200) NULL,
  extra_data    JSON NULL,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (id),
  CONSTRAINT fk_session_cycle
    FOREIGN KEY (cycle_id) REFERENCES cycle(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT chk_session_times CHECK (starts_at < ends_at)
) ENGINE=InnoDB;

CREATE INDEX idx_session_cycle_start ON session(cycle_id, starts_at);

-- =========================================================
-- PARTICIPANTES
-- =========================================================
CREATE TABLE participant (
  id                BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  event_id           BIGINT UNSIGNED NULL,
  name              VARCHAR(180) NOT NULL,
  email             VARCHAR(255) NULL,
  phone             VARCHAR(30)  NULL, -- almacenar en formato E.164 si es posible
  document_type     ENUM('DNI','CE','PASSPORT','OTRO') NOT NULL DEFAULT 'OTRO',
  document_number   VARCHAR(64) NOT NULL,
  extra_data        JSON NULL,
  created_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at        DATETIME NULL,

  PRIMARY KEY (id),
  CONSTRAINT fk_participant_city
    FOREIGN KEY (event_id) REFERENCES city(id)
    ON UPDATE CASCADE
    ON DELETE SET NULL,

  -- Unicidad de documento
  CONSTRAINT ux_participant_document UNIQUE (document_type, document_number),

  -- Unicidad por email (ignorando mayúsculas) — permite múltiples NULL
  CONSTRAINT ux_participant_email UNIQUE KEY ((LOWER(email)))
) ENGINE=InnoDB;

CREATE INDEX idx_participant_city ON participant(event_id);

-- =========================================================
-- INSCRIPCIONES (enrollment) separadas de asistencia
-- =========================================================
CREATE TABLE enrollment (
  id              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  participant_id  BIGINT UNSIGNED NOT NULL,
  cycle_id        BIGINT UNSIGNED NOT NULL,
  status          ENUM('pending','active','cancelled','completed') NOT NULL DEFAULT 'active',
  enrolled_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  extra_data      JSON NULL,

  PRIMARY KEY (id),
  CONSTRAINT fk_enrollment_participant
    FOREIGN KEY (participant_id) REFERENCES participant(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT fk_enrollment_cycle
    FOREIGN KEY (cycle_id) REFERENCES cycle(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,

  CONSTRAINT ux_enrollment UNIQUE (participant_id, cycle_id)
) ENGINE=InnoDB;

CREATE INDEX idx_enrollment_cycle ON enrollment(cycle_id);
CREATE INDEX idx_enrollment_participant ON enrollment(participant_id);

-- =========================================================
-- ASISTENCIA por sesión (attendance)
-- =========================================================
CREATE TABLE attendance (
  id             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  enrollment_id  BIGINT UNSIGNED NOT NULL,
  session_id     BIGINT UNSIGNED NOT NULL,
  status         ENUM('present','absent','late','excused') NOT NULL DEFAULT 'present',
  recorded_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  notes          VARCHAR(400) NULL,

  PRIMARY KEY (id),
  CONSTRAINT fk_attendance_enrollment
    FOREIGN KEY (enrollment_id) REFERENCES enrollment(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT fk_attendance_session
    FOREIGN KEY (session_id) REFERENCES session(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,

  CONSTRAINT ux_attendance UNIQUE (enrollment_id, session_id)
) ENGINE=InnoDB;

CREATE INDEX idx_attendance_session ON attendance(session_id);
CREATE INDEX idx_attendance_status ON attendance(status);

-- =========================================================
-- FORMULARIOS (esquema y UI en JSON; versionados)
-- Puedes asociarlo a una ciudad OR a un ciclo (exclusivo)
-- =========================================================
CREATE TABLE form (
  id            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  event_id       BIGINT UNSIGNED NULL,
  cycle_id      BIGINT UNSIGNED NULL,
  version       INT NOT NULL DEFAULT 1,
  title         VARCHAR(200) NOT NULL,
  schema_json   JSON NOT NULL,   -- definición de campos
  ui_json       JSON NULL,       -- estilos/orden
  active        BOOLEAN NOT NULL DEFAULT TRUE,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (id),
  CONSTRAINT fk_form_city
    FOREIGN KEY (event_id) REFERENCES city(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT fk_form_cycle
    FOREIGN KEY (cycle_id) REFERENCES cycle(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,

  -- exactamente uno de (event_id, cycle_id) debe estar presente
  CONSTRAINT chk_form_scope CHECK (
    (event_id IS NOT NULL) XOR (cycle_id IS NOT NULL)
  )
) ENGINE=InnoDB;

CREATE INDEX idx_form_city ON form(event_id);
CREATE INDEX idx_form_cycle ON form(cycle_id);
CREATE INDEX idx_form_active ON form(active);

-- =========================================================
-- RESPUESTAS A FORMULARIOS (una fila por envío)
-- =========================================================
CREATE TABLE form_submission (
  id             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  form_id        BIGINT UNSIGNED NOT NULL,
  participant_id BIGINT UNSIGNED NOT NULL,
  data_json      JSON NOT NULL,
  created_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (id),
  CONSTRAINT fk_submission_form
    FOREIGN KEY (form_id) REFERENCES form(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT fk_submission_participant
    FOREIGN KEY (participant_id) REFERENCES participant(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE INDEX idx_submission_form_participant ON form_submission(form_id, participant_id, created_at);

-- =========================================================
-- EJEMPLOS DE ÍNDICES SOBRE JSON (opcional)
-- Para consultar claves frecuentes en data_json puedes crear
-- columnas generadas e índices sobre ellas.
-- Ejemplo: índice por "genero" dentro de data_json:
-- ALTER TABLE form_submission
--   ADD COLUMN genero VARCHAR(20) GENERATED ALWAYS AS (JSON_UNQUOTE(data_json->'$.genero')) STORED,
--   ADD INDEX idx_submission_genero (genero);

-- =========================================================
-- VISTAS ÚTILES (opcionales)
-- =========================================================
-- Vista simple de asistencia resumida por sesión
CREATE OR REPLACE VIEW v_session_attendance AS
SELECT
  s.id           AS session_id,
  s.cycle_id,
  s.starts_at,
  s.ends_at,
  SUM(a.status = 'present') AS presents,
  SUM(a.status = 'absent')  AS absents,
  SUM(a.status = 'late')    AS lates,
  SUM(a.status = 'excused') AS excused
FROM session s
LEFT JOIN attendance a ON a.session_id = s.id
GROUP BY s.id;

-- Vista de inscripciones por ciclo/ciudad
CREATE OR REPLACE VIEW v_cycle_enrollments AS
SELECT
  c.id      AS cycle_id,
  c.name    AS cycle_name,
  ci.name   AS city_name,
  COUNT(e.id) AS enrollments
FROM cycle c
JOIN city ci ON ci.id = c.event_id
LEFT JOIN enrollment e ON e.cycle_id = c.id
GROUP BY c.id;

-- FIN DEL SCRIPT
