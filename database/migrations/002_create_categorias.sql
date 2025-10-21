-- Migración 002: Crear tabla categorias
-- Fecha: 2025-10-18
-- Descripción: Tabla para almacenar categorías de eventos con colores personalizados

-- Crear tabla categorias
CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL COMMENT 'Nombre de la categoría',
    color VARCHAR(7) NOT NULL DEFAULT '#6b7280' COMMENT 'Color hex de la categoría (ej: #ff0000)',
    descripcion TEXT NULL COMMENT 'Descripción opcional de la categoría',
    usuario_id INT NOT NULL COMMENT 'Usuario propietario de la categoría',
    activo TINYINT(1) DEFAULT 1 COMMENT 'Estado de la categoría: 1=activo, 0=inactivo',
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de creación del registro',
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de última actualización',
    
    -- Foreign key hacia usuarios
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabla de categorías de eventos';

-- Crear índices para optimizar consultas
CREATE INDEX idx_categorias_usuario ON categorias(usuario_id);
CREATE INDEX idx_categorias_activo ON categorias(activo);
CREATE INDEX idx_categorias_nombre_usuario ON categorias(nombre, usuario_id);

