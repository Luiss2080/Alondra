<?php
// Las vistas que incluyen este layout ya llamaron a session_start();
// aquí solo se expone el token CSRF para que el JavaScript del
// dashboard lo mande en las llamadas fetch que crean/editan/eliminan
// datos (ver config/csrf.php).
require_once __DIR__ . '/../../config/csrf.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
    <title>Alondra - Sistema de Gestión</title>
    <link rel="stylesheet" href="../../public/css/dashboard.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
        <i class="fas fa-bars"></i>
    </button>
    
    <div class="dashboard-layout">
        <?php include __DIR__ . '/sidebar.php'; ?>
        
        <div class="main-dashboard-content">
