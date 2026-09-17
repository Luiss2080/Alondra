<?php

namespace Tests\Validation;

use App\Validation\PasswordPolicy;
use PHPUnit\Framework\TestCase;

final class PasswordPolicyTest extends TestCase
{
    /**
     * @dataProvider invalidPasswords
     */
    public function testRejectsInvalidPasswords(string $password): void
    {
        $this->assertFalse(PasswordPolicy::isValid($password));
    }

    public static function invalidPasswords(): array
    {
        return [
            'empty string' => [''],
            'too short with letters and numbers' => ['abc123'],
            'only letters, long enough' => ['soloelletras'],
            'only numbers, long enough' => ['12345678'],
            'exactly seven characters' => ['abcdef1'],
        ];
    }

    /**
     * @dataProvider validPasswords
     */
    public function testAcceptsValidPasswords(string $password): void
    {
        $this->assertTrue(PasswordPolicy::isValid($password));
    }

    public static function validPasswords(): array
    {
        return [
            'exactly minimum length' => ['abcdefg1'],
            'longer with symbols' => ['C0ntraseñaSegura!'],
            'letters then numbers' => ['password1234'],
        ];
    }

    public function testRequirementsMessageMentionsMinimumLength(): void
    {
        $this->assertStringContainsString(
            (string) PasswordPolicy::MIN_LENGTH,
            PasswordPolicy::requirementsMessage()
        );
    }
}
