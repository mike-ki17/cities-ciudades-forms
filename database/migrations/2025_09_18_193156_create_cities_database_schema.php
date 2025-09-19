<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Crear la base de datos si no existe
        DB::unprepared("CREATE DATABASE IF NOT EXISTS cities_db CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;");
        
        // Usar la base de datos cities_db
        DB::unprepared("USE cities_db;");
        
        // Crear tablas una por una para evitar problemas de dependencias
        $this->createCityTable();
        $this->createCycleTable();
        $this->createSessionTable();
        $this->createParticipantTable();
        $this->createEnrollmentTable();
        $this->createAttendanceTable();
        $this->createFormTable();
        $this->createFormSubmissionTable();
        // Las vistas e índices se crearán después con migraciones separadas
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No hacer nada en el down para mantener la base de datos
        // ya que es un esquema completo que debe persistir
    }

    private function createCityTable(): void
    {
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS city (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                name VARCHAR(100) NOT NULL,
                country VARCHAR(100) NOT NULL,
                created_at TIMESTAMP NULL DEFAULT NULL,
                updated_at TIMESTAMP NULL DEFAULT NULL,
                deleted_at TIMESTAMP NULL DEFAULT NULL,
                PRIMARY KEY (id),
                UNIQUE KEY city_name_unique (name),
                KEY city_deleted_at_index (deleted_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
        ");
    }

    private function createCycleTable(): void
    {
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS cycle (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                name VARCHAR(100) NOT NULL,
                start_date DATE NOT NULL,
                end_date DATE NOT NULL,
                is_active BOOLEAN NOT NULL DEFAULT FALSE,
                created_at TIMESTAMP NULL DEFAULT NULL,
                updated_at TIMESTAMP NULL DEFAULT NULL,
                deleted_at TIMESTAMP NULL DEFAULT NULL,
                PRIMARY KEY (id),
                KEY cycle_deleted_at_index (deleted_at),
                KEY cycle_active_index (is_active),
                KEY cycle_dates_index (start_date, end_date)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
        ");
    }

    private function createSessionTable(): void
    {
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS session (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                cycle_id BIGINT UNSIGNED NOT NULL,
                name VARCHAR(100) NOT NULL,
                session_date DATE NOT NULL,
                start_time TIME NOT NULL,
                end_time TIME NOT NULL,
                location VARCHAR(255) NOT NULL,
                max_participants INT UNSIGNED DEFAULT NULL,
                is_active BOOLEAN NOT NULL DEFAULT TRUE,
                created_at TIMESTAMP NULL DEFAULT NULL,
                updated_at TIMESTAMP NULL DEFAULT NULL,
                deleted_at TIMESTAMP NULL DEFAULT NULL,
                PRIMARY KEY (id),
                KEY session_cycle_id_foreign (cycle_id),
                KEY session_deleted_at_index (deleted_at),
                KEY session_active_index (is_active),
                KEY session_date_index (session_date),
                CONSTRAINT session_cycle_id_foreign FOREIGN KEY (cycle_id) REFERENCES cycle (id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
        ");
    }

    private function createParticipantTable(): void
    {
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS participant (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                first_name VARCHAR(100) NOT NULL,
                last_name VARCHAR(100) NOT NULL,
                email VARCHAR(255) NOT NULL,
                phone VARCHAR(20) DEFAULT NULL,
                document_type ENUM('DNI', 'CE', 'PASSPORT', 'OTRO') NOT NULL,
                document_number VARCHAR(50) NOT NULL,
                birth_date DATE DEFAULT NULL,
                city_id BIGINT UNSIGNED DEFAULT NULL,
                created_at TIMESTAMP NULL DEFAULT NULL,
                updated_at TIMESTAMP NULL DEFAULT NULL,
                deleted_at TIMESTAMP NULL DEFAULT NULL,
                PRIMARY KEY (id),
                UNIQUE KEY participant_email_unique (email),
                UNIQUE KEY participant_document_unique (document_type, document_number),
                KEY participant_city_id_foreign (city_id),
                KEY participant_deleted_at_index (deleted_at),
                KEY participant_email_index (email),
                CONSTRAINT participant_city_id_foreign FOREIGN KEY (city_id) REFERENCES city (id) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
        ");
    }

    private function createEnrollmentTable(): void
    {
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS enrollment (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                participant_id BIGINT UNSIGNED NOT NULL,
                cycle_id BIGINT UNSIGNED NOT NULL,
                enrollment_date DATE NOT NULL,
                status ENUM('PENDING', 'CONFIRMED', 'CANCELLED', 'COMPLETED') NOT NULL DEFAULT 'PENDING',
                notes TEXT DEFAULT NULL,
                created_at TIMESTAMP NULL DEFAULT NULL,
                updated_at TIMESTAMP NULL DEFAULT NULL,
                deleted_at TIMESTAMP NULL DEFAULT NULL,
                PRIMARY KEY (id),
                UNIQUE KEY enrollment_participant_cycle_unique (participant_id, cycle_id),
                KEY enrollment_cycle_id_foreign (cycle_id),
                KEY enrollment_deleted_at_index (deleted_at),
                KEY enrollment_status_index (status),
                KEY enrollment_date_index (enrollment_date),
                CONSTRAINT enrollment_cycle_id_foreign FOREIGN KEY (cycle_id) REFERENCES cycle (id) ON DELETE CASCADE,
                CONSTRAINT enrollment_participant_id_foreign FOREIGN KEY (participant_id) REFERENCES participant (id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
        ");
    }

    private function createAttendanceTable(): void
    {
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS attendance (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                enrollment_id BIGINT UNSIGNED NOT NULL,
                session_id BIGINT UNSIGNED NOT NULL,
                attendance_date DATE NOT NULL,
                check_in_time TIME DEFAULT NULL,
                check_out_time TIME DEFAULT NULL,
                status ENUM('PRESENT', 'ABSENT', 'LATE', 'EXCUSED') NOT NULL DEFAULT 'ABSENT',
                notes TEXT DEFAULT NULL,
                created_at TIMESTAMP NULL DEFAULT NULL,
                updated_at TIMESTAMP NULL DEFAULT NULL,
                deleted_at TIMESTAMP NULL DEFAULT NULL,
                PRIMARY KEY (id),
                UNIQUE KEY attendance_enrollment_session_unique (enrollment_id, session_id),
                KEY attendance_session_id_foreign (session_id),
                KEY attendance_deleted_at_index (deleted_at),
                KEY attendance_status_index (status),
                KEY attendance_date_index (attendance_date),
                CONSTRAINT attendance_enrollment_id_foreign FOREIGN KEY (enrollment_id) REFERENCES enrollment (id) ON DELETE CASCADE,
                CONSTRAINT attendance_session_id_foreign FOREIGN KEY (session_id) REFERENCES session (id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
        ");
    }

    private function createFormTable(): void
    {
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS form (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                city_id BIGINT UNSIGNED DEFAULT NULL,
                title VARCHAR(255) NOT NULL,
                description TEXT DEFAULT NULL,
                schema_json JSON NOT NULL,
                is_active BOOLEAN NOT NULL DEFAULT TRUE,
                version INT UNSIGNED NOT NULL DEFAULT 1,
                created_at TIMESTAMP NULL DEFAULT NULL,
                updated_at TIMESTAMP NULL DEFAULT NULL,
                deleted_at TIMESTAMP NULL DEFAULT NULL,
                PRIMARY KEY (id),
                KEY form_city_id_foreign (city_id),
                KEY form_deleted_at_index (deleted_at),
                KEY form_active_index (is_active),
                KEY form_version_index (version),
                CONSTRAINT form_city_id_foreign FOREIGN KEY (city_id) REFERENCES city (id) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
        ");
    }

    private function createFormSubmissionTable(): void
    {
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS form_submission (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                form_id BIGINT UNSIGNED NOT NULL,
                participant_id BIGINT UNSIGNED NOT NULL,
                data_json JSON NOT NULL,
                submitted_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                created_at TIMESTAMP NULL DEFAULT NULL,
                updated_at TIMESTAMP NULL DEFAULT NULL,
                deleted_at TIMESTAMP NULL DEFAULT NULL,
                PRIMARY KEY (id),
                KEY form_submission_form_id_foreign (form_id),
                KEY form_submission_participant_id_foreign (participant_id),
                KEY form_submission_deleted_at_index (deleted_at),
                KEY form_submission_submitted_at_index (submitted_at),
                CONSTRAINT form_submission_form_id_foreign FOREIGN KEY (form_id) REFERENCES form (id) ON DELETE CASCADE,
                CONSTRAINT form_submission_participant_id_foreign FOREIGN KEY (participant_id) REFERENCES participant (id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
        ");
    }


};