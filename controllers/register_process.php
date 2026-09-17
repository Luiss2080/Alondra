<?php
// Controlador para procesar registro de usuarios
session_start();
require __DIR__ . '/../config/csrf.php';
$pdo = require __DIR__ . '/../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        $_SESSION['error'] = 'Tu sesión expiró o la solicitud no es válida. Intenta de nuevo.';
        header('Location: ../auth/register.php');
        exit;
    }

    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$nombre || !$email || !$password) {
        $_SESSION['error'] = 'Por favor completa todos los campos.';
        header('Location: ../auth/register.php');
        exit;
    }

    // Validar email básico
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Correo electrónico inválido.';
        header('Location: ../auth/register.php');
        exit;
    }

    // Hashear contraseña
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // Insertar en la BD (incluir campo activo con valor por defecto)
    $stmt = $pdo->prepare('INSERT INTO usuarios (nombre, email, password, activo) VALUES (?, ?, ?, 1)');
    try {
        // Iniciar transacción
        $pdo->beginTransaction();
        
        // Crear usuario
        $stmt->execute([$nombre, $email, $hash]);
        $usuario_id = $pdo->lastInsertId();
        
        // Crear categorías por defecto para el nuevo usuario
        $categorias_defecto = [
            ['Trabajo', '#6a3bd6', 'Reuniones, proyectos y actividades laborales'],
            ['Personal', '#10b981', 'Actividades personales y tiempo libre'],
            ['Salud', '#f59e0b', 'Citas médicas y actividades relacionadas con la salud'],
            ['Educación', '#8b5cf6', 'Cursos, clases y actividades de aprendizaje'],
            ['Emergencia', '#ef4444', 'Eventos urgentes que requieren atención inmediata']
        ];
        
        $stmt_categoria = $pdo->prepare('INSERT INTO categorias (usuario_id, nombre, color, descripcion, activo) VALUES (?, ?, ?, ?, 1)');
        
        foreach ($categorias_defecto as $categoria) {
            $stmt_categoria->execute([$usuario_id, $categoria[0], $categoria[1], $categoria[2]]);
        }
        
        // Confirmar transacción
        $pdo->commit();
        
        $_SESSION['success'] = 'Registro exitoso. Se han creado categorías por defecto para ti. Ahora puedes iniciar sesión.';
        header('Location: ../auth/login.php');
        exit;
    } catch (PDOException $e) {
        // Revertir transacción en caso de error
        $pdo->rollBack();
        
        // Si email duplicado o error de BD
        if ($e->getCode() == 23000) { // Duplicate entry
            $_SESSION['error'] = 'El correo electrónico ya está registrado.';
        } else {
            $_SESSION['error'] = 'Error al registrar usuario. Inténtalo de nuevo.';
        }
        header('Location: ../auth/register.php');
        exit;
    }
}

// Si se accede por GET, redirigir a la vista de registro
header('Location: ../auth/register.php');
exit;
