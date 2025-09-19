<?php

// Configuración de la base de datos MySQL
$host = '127.0.0.1';
$port = '3306';
$database = 'cities_db';
$username = 'root';
$password = '';

try {
    // Conectar a MySQL
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✓ Conexión a MySQL exitosa\n";
    echo "✓ Base de datos: $database\n";
    
    // Verificar si las tablas existen
    $tables = ['city', 'cycle', 'session', 'participant', 'enrollment', 'attendance', 'form', 'form_submission'];
    
    echo "\nVerificando tablas existentes:\n";
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "✓ Tabla $table existe\n";
        } else {
            echo "✗ Tabla $table NO existe\n";
        }
    }
    
    // Si no existe la tabla city, crearla
    if (!$pdo->query("SHOW TABLES LIKE 'city'")->rowCount()) {
        echo "\nCreando tablas...\n";
        
        // Create city table
        $pdo->exec("
            CREATE TABLE city (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                name VARCHAR(100) NOT NULL,
                country VARCHAR(100) NOT NULL,
                created_at TIMESTAMP NULL DEFAULT NULL,
                updated_at TIMESTAMP NULL DEFAULT NULL,
                deleted_at TIMESTAMP NULL DEFAULT NULL,
                PRIMARY KEY (id),
                UNIQUE KEY city_name_unique (name),
                KEY city_deleted_at_index (deleted_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
        ");
        echo "✓ Tabla city creada\n";
        
        // Create cycle table
        $pdo->exec("
            CREATE TABLE cycle (
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
        ");
        echo "✓ Tabla cycle creada\n";
        
        // Create session table
        $pdo->exec("
            CREATE TABLE session (
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
        ");
        echo "✓ Tabla session creada\n";
        
        // Create participant table
        $pdo->exec("
            CREATE TABLE participant (
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
        ");
        echo "✓ Tabla participant creada\n";
        
        // Create enrollment table
        $pdo->exec("
            CREATE TABLE enrollment (
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
        ");
        echo "✓ Tabla enrollment creada\n";
        
        // Create attendance table
        $pdo->exec("
            CREATE TABLE attendance (
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
        ");
        echo "✓ Tabla attendance creada\n";
        
        // Create form table
        $pdo->exec("
            CREATE TABLE form (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                city_id BIGINT UNSIGNED DEFAULT NULL,
                name VARCHAR(255) NOT NULL,
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
        ");
        echo "✓ Tabla form creada\n";
        
        // Create form_submission table
        $pdo->exec("
            CREATE TABLE form_submission (
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
        ");
        echo "✓ Tabla form_submission creada\n";
        
        // Insert sample data
        $pdo->exec("
            INSERT INTO city (id, name, country, created_at, updated_at) VALUES 
            (1, 'Bogotá', 'Colombia', NOW(), NOW()),
            (2, 'Medellín', 'Colombia', NOW(), NOW()),
            (3, 'Cali', 'Colombia', NOW(), NOW())
        ");
        echo "✓ Datos de ciudades insertados\n";
        
        // Insert sample form
        $pdo->exec("
            INSERT INTO form (id, city_id, name, description, schema_json, is_active, version, created_at, updated_at) VALUES 
            (1, 1, 'Formulario de Inscripción', 'Formulario para inscripción de participantes', '{\"type\":\"object\",\"properties\":{\"name\":{\"type\":\"string\",\"title\":\"Nombre completo\"},\"email\":{\"type\":\"string\",\"title\":\"Correo electrónico\",\"format\":\"email\"},\"phone\":{\"type\":\"string\",\"title\":\"Teléfono\"},\"age\":{\"type\":\"number\",\"title\":\"Edad\"}},\"required\":[\"name\",\"email\"]}', 1, 1, NOW(), NOW())
        ");
        echo "✓ Formulario de ejemplo insertado\n";
        
        echo "\n¡Base de datos configurada correctamente!\n";
    } else {
        echo "\nLas tablas ya existen. La base de datos está configurada.\n";
    }
    
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage() . "\n";
    echo "Verifica que MySQL esté ejecutándose y que la base de datos 'cities_db' exista.\n";
    exit(1);
}
