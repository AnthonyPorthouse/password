<?php
declare(strict_types=1);

namespace Groundsix\Password\Tests\Validators;

use Groundsix\Password\PasswordException;
use Groundsix\Password\Validators\PasswordBlacklistValidator;
use PHPUnit\Framework\TestCase;

class PasswordBlacklistValidatorTest extends TestCase
{
    /** @var PasswordBlacklistValidator $validator */
    private $validator;

    public function setUp(): void
    {
        $this->validator = new PasswordBlacklistValidator();
    }

    /**
     * @dataProvider validPasswords
     * @param string $password
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
