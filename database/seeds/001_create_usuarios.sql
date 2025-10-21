-- Seed 001: Datos iniciales para tabla usuarios
-- Fecha: 2025-10-17
-- Descripción: Usuarios de ejemplo para testing del sistema

-- Insertar usuarios de ejemplo
-- Nota: Las contraseñas están hasheadas con password_hash() de PHP
-- Contraseña para todos los usuarios de ejemplo: "123456"

INSERT INTO usuarios (nombre, email, password, activo) VALUES 
(
    'Administrador del Sistema', 
    'admin@alondra.edu', 
    '$2y$10$3qyFFQwwlR9ga6GwbaJw7uZ4Hti0QuImUR7WnH6dcUT0ewNIv/sCC', 
    1
),
(
    'María González', 
    'maria.gonzalez@estudiante.edu', 
    '$2y$10$3qyFFQwwlR9ga6GwbaJw7uZ4Hti0QuImUR7WnH6dcUT0ewNIv/sCC', 
    1
),
(
    'Juan Pérez', 
    'juan.perez@estudiante.edu', 
    '$2y$10$3qyFFQwwlR9ga6GwbaJw7uZ4Hti0QuImUR7WnH6dcUT0ewNIv/sCC', 
    1
),
(
    'Ana López', 
    'ana.lopez@estudiante.edu', 
    '$2y$10$3qyFFQwwlR9ga6GwbaJw7uZ4Hti0QuImUR7WnH6dcUT0ewNIv/sCC', 
    1
);

-- Verificar que los datos se insertaron correctamente
SELECT 'Usuarios insertados correctamente' AS resultado;
SELECT COUNT(*) as total_usuarios FROM usuarios;