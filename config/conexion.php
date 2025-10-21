<?php
// Conexión a la base de datos usando PDO
// Este archivo solo maneja la conexión - las tablas se crean mediante migraciones

$cfg = require __DIR__ . '/config.php';

try {
    $dsn = "mysql:host={$cfg['DB_HOST']};dbname={$cfg['DB_NAME']};charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $pdo = new PDO($dsn, $cfg['DB_USER'], $cfg['DB_PASS'], $options);
} catch (PDOException $e) {
    // En producción no mostrar detalles sensibles
    die('Error al conectar con la base de datos: ' . $e->getMessage());
}

// Exportar $pdo para archivos que incluyan este script
return $pdo;