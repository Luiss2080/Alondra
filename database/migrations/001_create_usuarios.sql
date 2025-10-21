-- Migración 001: Crear tabla usuarios
-- Fecha: 2025-10-17
-- Descripción: Tabla principal para almacenar información de usuarios del sistema

-- Crear tabla usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL COMMENT 'Nombre completo del usuario',
    email VARCHAR(150) NOT NULL UNIQUE COMMENT 'Correo electrónico único del usuario',
    password VARCHAR(255) NOT NULL COMMENT 'Contraseña hasheada del usuario',
    activo TINYINT(1) DEFAULT 1 COMMENT 'Estado del usuario: 1=activo, 0=inactivo',
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de creación del registro',
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de última actualización'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabla de usuarios del sistema';

-- Crear índices para optimizar consultas
CREATE INDEX idx_usuarios_email ON usuarios(email);
CREATE INDEX idx_usuarios_activo ON usuarios(activo);