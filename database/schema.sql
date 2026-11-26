CREATE DATABASE IF NOT EXISTS itla_crud_manager
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE itla_crud_manager;

CREATE TABLE IF NOT EXISTS estudiantes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    matricula VARCHAR(30) NOT NULL UNIQUE,
    nombre VARCHAR(80) NOT NULL,
    apellido VARCHAR(80) NOT NULL,
    correo VARCHAR(150) NOT NULL UNIQUE,
    carrera VARCHAR(120) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO estudiantes (matricula,nombre,apellido,correo,carrera) VALUES
('2024-1001','Ana','Martínez','ana.martinez@example.com','Desarrollo de Software'),
('2024-1002','Luis','Pérez','luis.perez@example.com','Redes de Información')
ON DUPLICATE KEY UPDATE matricula = VALUES(matricula);
