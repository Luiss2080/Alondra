<?php
// Configura el manejo de errores de PHP según APP_DEBUG.
// Se incluye desde config/conexion.php, que es el punto de entrada
// común a los controladores, las vistas y la API antes de tocar la
// base de datos.

$cfg = require __DIR__ . '/config.php';

if (!empty($cfg['APP_DEBUG'])) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    // En producción no se muestran errores ni advertencias de PHP al
    // usuario; solo quedan registrados en el log del servidor.
    error_reporting(E_ALL);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
}

return $cfg;
