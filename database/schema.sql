-- Base de datos para Sistema de Gestión de Documentos
-- Proyecto Final - Cloud Computing

CREATE DATABASE IF NOT EXISTS gestion_documentos;
USE gestion_documentos;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    rol ENUM('estudiante', 'profesor') NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_rol (rol)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de documentos
CREATE TABLE IF NOT EXISTS documentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT,
    nombre_archivo VARCHAR(255) NOT NULL,
    ruta_archivo VARCHAR(500) NOT NULL,
    tipo_archivo VARCHAR(50),
    tamano INT,
    usuario_id INT NOT NULL,
    fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_usuario (usuario_id),
    INDEX idx_fecha (fecha_subida)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar usuarios de prueba
-- Contraseña para ambos: 123456 (se debe hashear en PHP con password_hash)
INSERT INTO usuarios (username, password, nombre, email, rol) VALUES
('estudiante1', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'Juan Pérez', 'estudiante1@example.com', 'estudiante'),
('profesor1', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'María González', 'profesor1@example.com', 'profesor');

-- Vista para listar documentos con información del usuario
CREATE OR REPLACE VIEW vista_documentos AS
SELECT 
    d.id,
    d.titulo,
    d.descripcion,
    d.nombre_archivo,
    d.ruta_archivo,
    d.tipo_archivo,
    d.tamano,
    d.fecha_subida,
    u.username,
    u.nombre as nombre_usuario,
    u.rol
FROM documentos d
INNER JOIN usuarios u ON d.usuario_id = u.id;
