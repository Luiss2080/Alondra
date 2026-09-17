-- Migración 003: Crear tabla eventos
-- Fecha: 2025-10-18
-- Descripción: Tabla para almacenar eventos y tareas del calendario con soporte para categorías

-- Crear tabla eventos
CREATE TABLE IF NOT EXISTS eventos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL COMMENT 'ID del usuario propietario del evento',
    titulo VARCHAR(200) NOT NULL COMMENT 'Título del evento o tarea',
    descripcion TEXT COMMENT 'Descripción del evento',
    fecha_inicio DATE NOT NULL COMMENT 'Fecha del evento',
    hora_inicio TIME DEFAULT NULL COMMENT 'Hora de inicio (opcional)',
    hora_fin TIME DEFAULT NULL COMMENT 'Hora de fin (opcional)',
    tipo VARCHAR(50) DEFAULT 'tarea' COMMENT 'Tipo: tarea, evento, recordatorio',
    color VARCHAR(7) DEFAULT '#6a3bd6' COMMENT 'Color para mostrar en el calendario',
    categoria_id INT DEFAULT NULL COMMENT 'ID de la categoría del evento',
    activo TINYINT(1) DEFAULT 1 COMMENT 'Estado del evento: 1=activo, 0=inactivo',
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de creación del registro',
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de última actualización',
    
    -- Claves foráneas
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabla de eventos del calendario';

-- Crear índices para optimizar consultas
-- Nota: MySQL (a diferencia de MariaDB) no soporta la cláusula
-- "IF NOT EXISTS" en CREATE INDEX; como esta es una migración nueva
-- que se ejecuta una sola vez sobre una tabla recién creada, se omite
-- para que el script funcione igual en MySQL y MariaDB.
CREATE INDEX idx_eventos_usuario ON eventos(usuario_id);
CREATE INDEX idx_eventos_fecha ON eventos(fecha_inicio);
CREATE INDEX idx_eventos_categoria ON eventos(categoria_id);
CREATE INDEX idx_eventos_activo ON eventos(activo);
CREATE INDEX idx_eventos_usuario_fecha ON eventos(usuario_id, fecha_inicio);