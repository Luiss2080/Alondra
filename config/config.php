<?php
// Archivo de configuración principal.
// Los valores se leen de un archivo .env en la raíz del proyecto cuando
// existe; si una variable no está definida ahí (ni en el entorno del
// sistema operativo), se usa el valor por defecto pensado para un
// entorno local de XAMPP/Laragon.

/**
 * Lee un archivo .env sencillo (KEY=VALUE por línea) sin depender de
 * librerías externas. Ignora líneas vacías y comentarios ("#").
 */
function alondra_load_env(string $path): array
{
    $vars = [];

    if (!is_readable($path)) {
        return $vars;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);

        if ($line === '' || strpos($line, '#') === 0 || strpos($line, '=') === false) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);

        // Quitar comillas envolventes si el valor las tiene.
        $length = strlen($value);
        if ($length >= 2) {
            $first = $value[0];
            $last = $value[$length - 1];
            if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
                $value = substr($value, 1, -1);
            }
        }

        $vars[$key] = $value;
    }

    return $vars;
}

/**
 * Obtiene una variable de configuración: primero del .env cargado,
 * luego de las variables de entorno del sistema y, si no existe en
 * ninguna, del valor por defecto indicado.
 */
function alondra_env(array $envVars, string $key, $default = null)
{
    if (array_key_exists($key, $envVars) && $envVars[$key] !== '') {
        return $envVars[$key];
    }

    $fromSystem = getenv($key);
    if ($fromSystem !== false && $fromSystem !== '') {
        return $fromSystem;
    }

    return $default;
}

$envVars = alondra_load_env(__DIR__ . '/../.env');

return [
    // Cambia estos valores según tu entorno definiendo un archivo .env
    // (ver .env.example) en vez de editar este archivo directamente.
    'DB_HOST' => alondra_env($envVars, 'DB_HOST', '127.0.0.1'),
    'DB_NAME' => alondra_env($envVars, 'DB_NAME', 'alondra'),
    'DB_USER' => alondra_env($envVars, 'DB_USER', 'root'),
    'DB_PASS' => alondra_env($envVars, 'DB_PASS', ''),
    'BASE_URL' => alondra_env($envVars, 'BASE_URL', 'http://localhost/Alondra'),

    // Cuando es verdadero, se muestran mensajes de error detallados
    // (incluidos los de conexión a la base de datos). Debe quedar en
    // "false" en cualquier entorno accesible públicamente.
    'APP_DEBUG' => filter_var(
        alondra_env($envVars, 'APP_DEBUG', 'true'),
        FILTER_VALIDATE_BOOLEAN
    ),
];
