<?php
// Controlador para procesar inicio de sesión
session_start();
require __DIR__ . '/../config/csrf.php';
$pdo = require __DIR__ . '/../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        $_SESSION['error'] = 'Tu sesión expiró o la solicitud no es válida. Intenta de nuevo.';
        header('Location: ../auth/login.php');
        exit;
    }

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $_SESSION['error'] = 'Por favor ingresa correo y contraseña.';
        header('Location: ../auth/login.php');
        exit;
    }

    $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = ? AND activo = 1 LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Autenticación exitosa
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = [
            'id' => $user['id'],
            'nombre' => $user['nombre'],
            'email' => $user['email']
        ];
        header('Location: ../views/home/dashboard.php');
        exit;
    }

    $_SESSION['error'] = 'Credenciales incorrectas o usuario inactivo.';
    header('Location: ../auth/login.php');
    exit;
}

header('Location: ../auth/login.php');
exit;
