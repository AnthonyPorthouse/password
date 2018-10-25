<?php
declare(strict_types=1);

namespace Porthou\Password\Tests\Validators;

use Porthou\Password\PasswordException;
use Porthou\Password\Validators\PasswordBlacklistValidator;
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
     * @throws PasswordException
     */
    public function testCustomBlacklist(): void
    {
        $validator = new PasswordBlacklistValidator(__DIR__ . '/assets/blacklist.txt');
        $this->expectException(PasswordException::class);
        $validator->validate('password');
        $this->assertTrue($validator->validate('test1234'));
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

    public function testMissingFile(): void
    {
        $validator = new PasswordBlacklistValidator(__DIR__ . '/assets/doesnotexist.txt');
        $this->assertTrue($validator->validate('12345'));
    }
}
