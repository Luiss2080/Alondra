<?php
/**
 * Protección CSRF basada en un token por sesión (patrón "synchronizer
 * token"). Requiere que session_start() ya se haya llamado antes de
 * incluir este archivo.
 */

/**
 * Devuelve el token CSRF de la sesión actual, generando uno nuevo la
 * primera vez.
 */
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

/**
 * Imprime un <input type="hidden"> listo para usarse dentro de un
 * formulario HTML clásico (login, registro, etc.).
 */
function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
}

/**
 * Compara el token recibido (de un formulario o de la cabecera
 * X-CSRF-Token en las llamadas fetch) contra el de la sesión, usando
 * hash_equals para evitar timing attacks.
 */
function csrf_verify(?string $token): bool
{
    return !empty($_SESSION['csrf_token']) && is_string($token) && hash_equals($_SESSION['csrf_token'], $token);
}
