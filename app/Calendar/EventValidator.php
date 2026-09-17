<?php

namespace App\Calendar;

/**
 * Reglas de validación para los datos de un evento/tarea del
 * calendario, extraídas de api/events.php para poder probarlas de
 * forma aislada (sin base de datos) y para que la propia API deje de
 * aceptar fechas/horas con cualquier formato.
 */
class EventValidator
{
    public const TIPOS_VALIDOS = ['evento', 'tarea', 'recordatorio'];

    /**
     * Valida que la fecha tenga formato YYYY-MM-DD y sea una fecha de
     * calendario real (rechaza "2025-02-30", por ejemplo).
     */
    public static function isValidDate(?string $fecha): bool
    {
        if ($fecha === null || $fecha === '') {
            return false;
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            return false;
        }

        [$year, $month, $day] = array_map('intval', explode('-', $fecha));

        return checkdate($month, $day, $year);
    }

    /**
     * Valida formato de hora HH:MM o HH:MM:SS (24 horas). Un campo
     * vacío o null se considera válido porque hora_inicio/hora_fin son
     * opcionales en el esquema.
     */
    public static function isValidTime(?string $hora): bool
    {
        if ($hora === null || $hora === '') {
            return true;
        }

        return (bool) preg_match('/^([01]\d|2[0-3]):[0-5]\d(:[0-5]\d)?$/', $hora);
    }

    /**
     * Si ambas horas están presentes, la de fin debe ser posterior a
     * la de inicio. Si falta alguna, no hay nada que comparar.
     */
    public static function isEndAfterStart(?string $horaInicio, ?string $horaFin): bool
    {
        if (empty($horaInicio) || empty($horaFin)) {
            return true;
        }

        return strcmp($horaFin, $horaInicio) > 0;
    }

    /**
     * El tipo debe ser uno de los que la interfaz realmente ofrece.
     */
    public static function isValidType(?string $tipo): bool
    {
        return in_array($tipo, self::TIPOS_VALIDOS, true);
    }

    /**
     * Ejecuta todas las validaciones sobre los datos de un evento y
     * devuelve la lista de mensajes de error (vacía si todo es
     * válido). $tipo se valida solo si viene informado, ya que
     * api/events.php le aplica 'evento' por defecto antes de llegar
     * aquí.
     */
    public static function validate(array $datos): array
    {
        $errores = [];

        if (empty($datos['titulo'])) {
            $errores[] = 'El título es obligatorio.';
        }

        if (!self::isValidDate($datos['fecha_inicio'] ?? null)) {
            $errores[] = 'La fecha de inicio es obligatoria y debe tener formato YYYY-MM-DD.';
        }

        if (!self::isValidTime($datos['hora_inicio'] ?? null)) {
            $errores[] = 'La hora de inicio no tiene un formato válido (HH:MM).';
        }

        if (!self::isValidTime($datos['hora_fin'] ?? null)) {
            $errores[] = 'La hora de fin no tiene un formato válido (HH:MM).';
        }

        if (!self::isEndAfterStart($datos['hora_inicio'] ?? null, $datos['hora_fin'] ?? null)) {
            $errores[] = 'La hora de fin debe ser posterior a la hora de inicio.';
        }

        if (!empty($datos['tipo']) && !self::isValidType($datos['tipo'])) {
            $errores[] = 'El tipo debe ser evento, tarea o recordatorio.';
        }

        return $errores;
    }
}
