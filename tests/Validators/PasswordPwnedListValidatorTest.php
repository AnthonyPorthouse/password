<?php
declare(strict_types=1);

namespace Porthou\Password\Tests\Validators;

use Porthou\Password\PasswordException;
use Porthou\Password\Validators\PasswordPwnedListValidator;
use PHPUnit\Framework\TestCase;

class PasswordPwnedListValidatorTest extends TestCase
{
    /** @var PasswordPwnedListValidator $validator */
    private $validator;

    public function setUp(): void
    {
        $this->validator = new PasswordPwnedListValidator(__DIR__ . '/assets/pwndlist.txt', 1);
    }

    /**
     * @throws PasswordException
     */
    public function testPwnedCount(): void
    {
        $validator = new PasswordPwnedListValidator(__DIR__ . '/assets/pwndlist.txt', 10);
        $this->assertTrue($validator->validate('password'));

        $validator = new PasswordPwnedListValidator(__DIR__ . '/assets/pwndlist.txt', 5);
        $this->expectException(PasswordException::class);
        $validator->validate('password');
    }

    /**
     * @dataProvider validPasswords
     * @param string $password
     * @throws PasswordException
     */
    public function testValidPasswords(string $password): void
    {
        $result = $this->validator->validate($password);
        $this->assertTrue($result);
    }

    public function validPasswords(): array
    {
        return [
            ['a;cjbasda'],
            ['💩💩💩💩💩💩💩💩'],
        ];
    }

    /**
     * @dataProvider invalidPasswords
     * @param string $password
     * @throws PasswordException
     */
    public function testInvalidPasswords(string $password): void
    {
        $this->expectException(PasswordException::class);
        $this->validator->validate($password);
    }

    public function invalidPasswords(): array
    {
        return [
            ['123456'],
            ['password'],
            ['qwertyuiop'],
            ['dragon'],
        ];
    }
}
