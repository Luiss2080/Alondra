<?php
// Conexión a la base de datos usando PDO
// Este archivo solo maneja la conexión - las tablas se crean mediante migraciones

$cfg = require __DIR__ . '/bootstrap.php';

try {
    $dsn = "mysql:host={$cfg['DB_HOST']};dbname={$cfg['DB_NAME']};charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $pdo = new PDO($dsn, $cfg['DB_USER'], $cfg['DB_PASS'], $options);
} catch (PDOException $e) {
    // El detalle real (que puede incluir el host o el nombre de la base
    // de datos) siempre queda en el log del servidor, nunca solo en
    // pantalla.
    error_log('Error de conexión a la base de datos: ' . $e->getMessage());

    http_response_code(500);

    if (!empty($cfg['APP_DEBUG'])) {
        die('Error al conectar con la base de datos: ' . $e->getMessage());
    }

    die('No se pudo conectar con el servicio en este momento. Inténtalo más tarde.');
}

// Exportar $pdo para archivos que incluyan este script
return $pdo;