<?php

namespace Tests\Calendar;

use App\Calendar\EventValidator;
use PHPUnit\Framework\TestCase;

final class EventValidatorTest extends TestCase
{
    /**
     * @dataProvider validDates
     */
    public function testIsValidDateAcceptsRealCalendarDates(string $date): void
    {
        $this->assertTrue(EventValidator::isValidDate($date));
    }

    public static function validDates(): array
    {
        return [
            'ordinary date' => ['2026-03-15'],
            'leap day on a leap year' => ['2028-02-29'],
            'last day of year' => ['2026-12-31'],
        ];
    }

    /**
     * @dataProvider invalidDates
     */
    public function testIsValidDateRejectsBadDates(?string $date): void
    {
        $this->assertFalse(EventValidator::isValidDate($date));
    }

    public static function invalidDates(): array
    {
        return [
            'null' => [null],
            'empty string' => [''],
            'wrong format (slashes)' => ['15/03/2026'],
            'day out of range' => ['2025-02-30'],
            'leap day on a non-leap year' => ['2027-02-29'],
            'month out of range' => ['2026-13-01'],
            'free text' => ['not a date'],
        ];
    }

    public function testIsValidTimeAcceptsEmptyAsOptional(): void
    {
        $this->assertTrue(EventValidator::isValidTime(null));
        $this->assertTrue(EventValidator::isValidTime(''));
    }

    /**
     * @dataProvider validTimes
     */
    public function testIsValidTimeAcceptsWellFormedTimes(string $time): void
    {
        $this->assertTrue(EventValidator::isValidTime($time));
    }

    public static function validTimes(): array
    {
        return [
            'HH:MM' => ['09:30'],
            'midnight' => ['00:00'],
            'last minute of day' => ['23:59'],
            'with seconds' => ['14:05:30'],
        ];
    }

    /**
     * @dataProvider invalidTimes
     */
    public function testIsValidTimeRejectsMalformedTimes(string $time): void
    {
        $this->assertFalse(EventValidator::isValidTime($time));
    }

    public static function invalidTimes(): array
    {
        return [
            'hour out of range' => ['24:00'],
            'minute out of range' => ['12:60'],
            'missing leading zero handling is fine, but bad separator' => ['12-30'],
            'free text' => ['mañana'],
        ];
    }

    public function testIsEndAfterStartTrueWhenEitherSideMissing(): void
    {
        $this->assertTrue(EventValidator::isEndAfterStart(null, null));
        $this->assertTrue(EventValidator::isEndAfterStart('09:00', null));
        $this->assertTrue(EventValidator::isEndAfterStart(null, '10:00'));
    }

    public function testIsEndAfterStartComparesBothTimes(): void
    {
        $this->assertTrue(EventValidator::isEndAfterStart('09:00', '10:00'));
        $this->assertFalse(EventValidator::isEndAfterStart('10:00', '09:00'));
        $this->assertFalse(EventValidator::isEndAfterStart('10:00', '10:00'));
    }

    public function testIsValidTypeOnlyAcceptsTheThreeKnownTypes(): void
    {
        $this->assertTrue(EventValidator::isValidType('evento'));
        $this->assertTrue(EventValidator::isValidType('tarea'));
        $this->assertTrue(EventValidator::isValidType('recordatorio'));
        $this->assertFalse(EventValidator::isValidType('otra-cosa'));
        $this->assertFalse(EventValidator::isValidType(null));
    }

    public function testValidateReturnsNoErrorsForAWellFormedEvent(): void
    {
        $errores = EventValidator::validate([
            'titulo' => 'Reunión de equipo',
            'fecha_inicio' => '2026-05-10',
            'hora_inicio' => '09:00',
            'hora_fin' => '10:00',
            'tipo' => 'evento',
        ]);

        $this->assertSame([], $errores);
    }

    public function testValidateCollectsEveryProblem(): void
    {
        $errores = EventValidator::validate([
            'titulo' => '',
            'fecha_inicio' => 'no-es-una-fecha',
            'hora_inicio' => '10:00',
            'hora_fin' => '09:00',
            'tipo' => 'invalido',
        ]);

        $this->assertCount(4, $errores);
    }
}
