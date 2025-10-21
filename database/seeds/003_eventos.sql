-- Seed 003: Datos iniciales para tabla eventos
-- Fecha: 2025-10-18
-- Descripción: Eventos de ejemplo con diferentes categorías para testing del sistema

-- Insertar eventos de ejemplo para diferentes usuarios
-- Nota: Los eventos están distribuidos en diferentes categorías para mostrar el sistema de colores
-- Usar INSERT IGNORE para evitar duplicados en múltiples ejecuciones

INSERT IGNORE INTO eventos (usuario_id, titulo, descripcion, fecha_inicio, hora_inicio, hora_fin, tipo, categoria_id, activo) VALUES 
-- Eventos de emergencia (rojos - alta prioridad)
(
    1, 
    'Revisión Médica Urgente', 
    'Cita médica de emergencia que no puede posponerse', 
    '2025-10-19', 
    '09:00:00', 
    '10:00:00', 
    'evento', 
    1,
    1
),
(
    1, 
    'Reunión Crisis Proyecto', 
    'Reunión urgente para resolver problemas críticos del proyecto', 
    '2025-10-20', 
    '14:00:00', 
    '16:00:00', 
    'evento', 
    1,
    1
),

-- Eventos de trabajo (morados)
(
    1, 
    'Presentación Trimestral', 
    'Presentación de resultados del tercer trimestre', 
    '2025-10-22', 
    '10:00:00', 
    '12:00:00', 
    'evento', 
    2,
    1
),
(
    2, 
    'Reunión de Equipo', 
    'Reunión semanal del equipo de desarrollo', 
    '2025-10-21', 
    '15:00:00', 
    '16:30:00', 
    'evento', 
    2,
    1
),

-- Eventos personales (verdes)
(
    1, 
    'Cumpleaños Familiar', 
    'Celebración de cumpleaños en familia', 
    '2025-10-25', 
    '18:00:00', 
    '22:00:00', 
    'evento', 
    3,
    1
),
(
    3, 
    'Ejercicio en el Gimnasio', 
    'Rutina de ejercicios personales', 
    '2025-10-19', 
    '07:00:00', 
    '08:30:00', 
    'tarea', 
    3,
    1
),

-- Eventos de salud (amarillos)
(
    2, 
    'Cita Dentista', 
    'Limpieza dental programada', 
    '2025-10-23', 
    '16:00:00', 
    '17:00:00', 
    'evento', 
    4,
    1
),
(
    3, 
    'Examen Médico Anual', 
    'Chequeo médico preventivo anual', 
    '2025-10-26', 
    '11:00:00', 
    '12:30:00', 
    'evento', 
    4,
    1
),

-- Eventos de educación (morado claro)
(
    2, 
    'Curso de Programación', 
    'Clase de PHP y bases de datos', 
    '2025-10-24', 
    '19:00:00', 
    '21:00:00', 
    'evento', 
    5,
    1
),
(
    3, 
    'Examen Final', 
    'Examen final del curso de matemáticas', 
    '2025-10-28', 
    '14:00:00', 
    '17:00:00', 
    'evento', 
    5,
    1
);

-- Verificar que los eventos se insertaron correctamente
SELECT 'Eventos insertados correctamente' AS resultado;
SELECT COUNT(*) as total_eventos FROM eventos;
SELECT 
    e.titulo, 
    e.fecha_inicio, 
    c.nombre as categoria, 
    c.color as color_categoria 
FROM eventos e 
LEFT JOIN categorias c ON e.categoria_id = c.id 
ORDER BY e.fecha_inicio, e.hora_inicio;