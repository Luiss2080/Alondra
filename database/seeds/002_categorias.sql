-- Seed 002: Datos iniciales para tabla categorias
-- Fecha: 2025-10-18
-- Descripción: Categorías predefinidas con colores para identificación de prioridades

-- Insertar categorías por defecto para el usuario administrador
-- Nota: Los colores están basados en un sistema de prioridades visuales
-- Usar INSERT IGNORE para evitar duplicados en caso de múltiples ejecuciones
INSERT IGNORE INTO categorias (nombre, color, descripcion, usuario_id, activo) VALUES 
(
    'Emergencia', 
    '#ef4444', 
    'Eventos urgentes que requieren atención inmediata - ALTA PRIORIDAD', 
    1,
    1
),
(
    'Trabajo', 
    '#6a3bd6', 
    'Reuniones, proyectos y actividades laborales', 
    1,
    1
),
(
    'Personal', 
    '#10b981', 
    'Actividades personales y tiempo libre', 
    1,
    1
),
(
    'Salud', 
    '#f59e0b', 
    'Citas médicas y actividades relacionadas con la salud', 
    1,
    1
),
(
    'Educación', 
    '#8b5cf6', 
    'Cursos, clases y actividades de aprendizaje', 
    1,
    1
);

-- Verificar que las categorías se insertaron correctamente
SELECT 'Categorías insertadas correctamente' AS resultado;
SELECT COUNT(*) as total_categorias FROM categorias;
SELECT nombre, color, descripcion FROM categorias ORDER BY id;