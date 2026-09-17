<?php

namespace App\Validation;

/**
 * Reglas mínimas de fortaleza de contraseña para el registro de
 * usuarios. Antes de esto, controllers/register_process.php aceptaba
 * cualquier contraseña no vacía (incluida "1"); password_hash() por sí
 * solo no protege contra contraseñas triviales.
 */
class PasswordPolicy
{
    public const MIN_LENGTH = 8;

    /**
     * Una contraseña válida tiene al menos MIN_LENGTH caracteres y
     * combina letras y números (evita contraseñas puramente numéricas
     * o puramente alfabéticas, sin exigir símbolos especiales que
     * suelen frustrar más de lo que protegen).
     */
    public static function isValid(string $password): bool
    {
        if (strlen($password) < self::MIN_LENGTH) {
            return false;
        }

        $tieneLetra = (bool) preg_match('/[A-Za-z]/', $password);
        $tieneNumero = (bool) preg_match('/[0-9]/', $password);

        return $tieneLetra && $tieneNumero;
    }

    /**
     * Mensaje de error listo para mostrar al usuario cuando isValid()
     * devuelve false.
     */
    public static function requirementsMessage(): string
    {
        return sprintf(
            'La contraseña debe tener al menos %d caracteres e incluir letras y números.',
            self::MIN_LENGTH
        );
    }
}
