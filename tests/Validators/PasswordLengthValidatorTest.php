<?php
declare(strict_types=1);

namespace Porthou\Password\Tests\Validators;

use Porthou\Password\PasswordException;
use Porthou\Password\Validator;
use Porthou\Password\Validators\PasswordLengthValidator;
use PHPUnit\Framework\TestCase;

class PasswordLengthValidatorTest extends TestCase
{
    /** @var Validator $validator */
    private $validator;

    public function setUp()
    {
        $this->validator = new PasswordLengthValidator();
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
            ['12345678'],
            ['123456789'],
            ['abcdefgh'],
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
            ['1234567'],
            ['💩💩💩💩💩💩💩'],
        ];
    }
}
