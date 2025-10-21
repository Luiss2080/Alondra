-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-10-2025 a las 01:07:37
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `alondra`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre de la categoría',
  `color` varchar(7) NOT NULL DEFAULT '#6b7280' COMMENT 'Color hex de la categoría (ej: #ff0000)',
  `descripcion` text DEFAULT NULL COMMENT 'Descripción opcional de la categoría',
  `usuario_id` int(11) NOT NULL COMMENT 'Usuario propietario de la categoría',
  `activo` tinyint(1) DEFAULT 1 COMMENT 'Estado de la categoría: 1=activo, 0=inactivo',
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Fecha de creación del registro',
  `actualizado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Fecha de última actualización'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabla de categorías de eventos';

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `color`, `descripcion`, `usuario_id`, `activo`, `creado_en`, `actualizado_en`) VALUES
(1, 'Emergencia', '#ef4444', 'Eventos urgentes que requieren atención inmediata - ALTA PRIORIDAD', 1, 1, '2025-10-18 18:13:26', '2025-10-18 18:13:26'),
(10, 'Trabajo', '#6a3bd6', 'Actividades laborales', 1, 1, '2025-10-18 22:03:48', '2025-10-18 22:03:48'),
(11, 'Personal', '#10b981', 'Actividades personales', 1, 1, '2025-10-18 22:03:48', '2025-10-18 22:03:48'),
(12, 'Salud', '#ef4444', 'Citas médicas y salud', 1, 1, '2025-10-18 22:03:48', '2025-10-18 22:03:48'),
(13, 'Educación', '#8b5cf6', 'Cursos y capacitaciones', 1, 1, '2025-10-18 22:03:48', '2025-10-18 22:03:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos`
--

CREATE TABLE `eventos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL COMMENT 'ID del usuario propietario del evento',
  `titulo` varchar(200) NOT NULL COMMENT 'Título del evento o tarea',
  `descripcion` text DEFAULT NULL COMMENT 'Descripción del evento',
  `fecha_inicio` date NOT NULL COMMENT 'Fecha del evento',
  `hora_inicio` time DEFAULT NULL COMMENT 'Hora de inicio (opcional)',
  `hora_fin` time DEFAULT NULL COMMENT 'Hora de fin (opcional)',
  `tipo` varchar(50) DEFAULT 'tarea' COMMENT 'Tipo: tarea, evento, recordatorio',
  `color` varchar(7) DEFAULT '#6a3bd6' COMMENT 'Color para mostrar en el calendario',
  `categoria_id` int(11) DEFAULT NULL COMMENT 'ID de la categoría del evento',
  `activo` tinyint(1) DEFAULT 1 COMMENT 'Estado del evento: 1=activo, 0=inactivo',
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Fecha de creación del registro',
  `actualizado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Fecha de última actualización'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabla de eventos del calendario';

--
-- Volcado de datos para la tabla `eventos`
--

INSERT INTO `eventos` (`id`, `usuario_id`, `titulo`, `descripcion`, `fecha_inicio`, `hora_inicio`, `hora_fin`, `tipo`, `color`, `categoria_id`, `activo`, `creado_en`, `actualizado_en`) VALUES
(4, 2, 'Reunión de Equipo', 'Reunión semanal del equipo de desarrollo', '2025-10-21', '15:00:00', '16:30:00', 'evento', '#6a3bd6', 10, 1, '2025-10-18 18:13:37', '2025-10-18 22:34:59'),
(6, 3, 'Ejercicio en el Gimnasio', 'Rutina de ejercicios personales', '2025-10-19', '07:00:00', '08:30:00', 'tarea', '#6a3bd6', 11, 1, '2025-10-18 18:13:37', '2025-10-18 22:34:59'),
(7, 2, 'Cita Dentista', 'Limpieza dental programada', '2025-10-23', '16:00:00', '17:00:00', 'evento', '#6a3bd6', 12, 1, '2025-10-18 18:13:37', '2025-10-18 22:34:59'),
(8, 3, 'Examen Médico Anual', 'Chequeo médico preventivo anual', '2025-10-26', '11:00:00', '12:30:00', 'evento', '#6a3bd6', 12, 1, '2025-10-18 18:13:37', '2025-10-18 22:34:59'),
(9, 2, 'Curso de Programación', 'Clase de PHP y bases de datos', '2025-10-24', '19:00:00', '21:00:00', 'evento', '#6a3bd6', 13, 1, '2025-10-18 18:13:37', '2025-10-18 22:34:59'),
(10, 3, 'Examen Final', 'Examen final del curso de matemáticas', '2025-10-28', '14:00:00', '17:00:00', 'evento', '#6a3bd6', 13, 1, '2025-10-18 18:13:37', '2025-10-18 22:34:59'),
(18, 1, 'Reunión de Marketing', 'Planificación de campaña Q4', '2025-10-20', '09:00:00', '10:30:00', 'evento', '#6a3bd6', 10, 1, '2025-10-18 22:03:48', '2025-10-18 22:03:48'),
(19, 1, 'Cita Médica', 'Control médico mensual', '2025-10-21', '15:30:00', '16:30:00', 'evento', '#6a3bd6', 12, 1, '2025-10-18 22:03:48', '2025-10-18 22:03:48'),
(20, 1, 'Capacitación Online', 'Curso de nuevas tecnologías', '2025-10-22', '10:00:00', '12:00:00', 'evento', '#6a3bd6', 13, 1, '2025-10-18 22:03:48', '2025-10-18 22:03:48'),
(21, 1, 'Almuerzo familiar', 'Reunión familiar fin de semana', '2025-10-23', '13:00:00', '15:00:00', 'evento', '#6a3bd6', 11, 1, '2025-10-18 22:03:48', '2025-10-18 22:03:48'),
(22, 1, 'Presentación proyecto', 'Entrega final del proyecto', '2025-10-24', '14:00:00', '16:00:00', 'evento', '#6a3bd6', 10, 1, '2025-10-18 22:03:48', '2025-10-18 22:03:48'),
(23, 1, 'Ejercicio', 'Rutina de ejercicios', '2025-10-25', '07:00:00', '08:30:00', 'tarea', '#6a3bd6', 12, 1, '2025-10-18 22:03:48', '2025-10-18 22:03:48'),
(24, 1, 'Revisión mensual', 'Evaluación de objetivos', '2025-10-26', '11:00:00', '12:00:00', 'evento', '#6a3bd6', 10, 1, '2025-10-18 22:03:48', '2025-10-18 22:03:48'),
(25, 1, 'Holaaa', 'test', '2025-10-20', '09:00:00', '10:00:00', 'tarea', '#ef4444', 12, 1, '2025-10-18 22:30:10', '2025-10-18 22:34:59'),
(26, 1, 'Trabajo escolar ', 'asfsfasf', '2025-10-31', '09:00:00', '12:00:00', 'tarea', '#ef4444', 1, 1, '2025-10-18 22:48:06', '2025-10-18 22:48:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre completo del usuario',
  `email` varchar(150) NOT NULL COMMENT 'Correo electrónico único del usuario',
  `password` varchar(255) NOT NULL COMMENT 'Contraseña hasheada del usuario',
  `activo` tinyint(1) DEFAULT 1 COMMENT 'Estado del usuario: 1=activo, 0=inactivo',
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Fecha de creación del registro',
  `actualizado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Fecha de última actualización'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabla de usuarios del sistema';

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `activo`, `creado_en`, `actualizado_en`) VALUES
(1, 'Administrador del Sistema', 'admin@alondra.edu', '$2y$10$3qyFFQwwlR9ga6GwbaJw7uZ4Hti0QuImUR7WnH6dcUT0ewNIv/sCC', 1, '2025-10-18 18:13:14', '2025-10-18 18:13:14'),
(2, 'María González', 'maria.gonzalez@estudiante.edu', '$2y$10$3qyFFQwwlR9ga6GwbaJw7uZ4Hti0QuImUR7WnH6dcUT0ewNIv/sCC', 1, '2025-10-18 18:13:14', '2025-10-18 18:13:14'),
(3, 'Juan Pérez', 'juan.perez@estudiante.edu', '$2y$10$3qyFFQwwlR9ga6GwbaJw7uZ4Hti0QuImUR7WnH6dcUT0ewNIv/sCC', 1, '2025-10-18 18:13:14', '2025-10-18 18:13:14'),
(4, 'Ana López', 'ana.lopez@estudiante.edu', '$2y$10$3qyFFQwwlR9ga6GwbaJw7uZ4Hti0QuImUR7WnH6dcUT0ewNIv/sCC', 1, '2025-10-18 18:13:14', '2025-10-18 18:13:14'),
(5, 'Luis Marioooooo', 'Luiss@alondra.edu', '$2y$10$MSO7vmeVSJ6Swt/F5qu33OFD6BzbV6gvl4FdaCzAG0o6lxgtR7ypK', 1, '2025-10-18 22:30:44', '2025-10-18 22:30:44'),
(6, 'Sandy', 'Sandy@gmail.com', '$2y$10$awYGHXbwRhuEkbhzHQoTP.PDRqxGOFhknyUKOMrf/SGI4Mxmphvhy', 1, '2025-10-18 23:01:22', '2025-10-18 23:01:22');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_categorias_usuario` (`usuario_id`),
  ADD KEY `idx_categorias_activo` (`activo`),
  ADD KEY `idx_categorias_nombre_usuario` (`nombre`,`usuario_id`);

--
-- Indices de la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_eventos_usuario` (`usuario_id`),
  ADD KEY `idx_eventos_fecha` (`fecha_inicio`),
  ADD KEY `idx_eventos_categoria` (`categoria_id`),
  ADD KEY `idx_eventos_activo` (`activo`),
  ADD KEY `idx_eventos_usuario_fecha` (`usuario_id`,`fecha_inicio`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_usuarios_email` (`email`),
  ADD KEY `idx_usuarios_activo` (`activo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD CONSTRAINT `categorias_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `eventos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `eventos_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
