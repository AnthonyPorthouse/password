<?php
declare(strict_types=1);

namespace Porthou\Password\Tests\Validators;

use Porthou\Password\PasswordException;
use Porthou\Password\Validators\PasswordMixedCaseValidator;
use PHPUnit\Framework\TestCase;
use function Sodium\crypto_box_publickey_from_secretkey;

class PasswordMixedCaseValidatorTest extends TestCase
{
    /** @var PasswordMixedCaseValidator $validator */
    private $validator;

    public function setUp(): void
    {
        $this->validator = new PasswordMixedCaseValidator();
    }

    /**
     * @dataProvider validPasswords
     * @param string $password
     */
    public function testValidPasswords(string $password): void
    {
        $this->assertTrue($this->validator->validate($password));
    }

    public function validPasswords(): array
    {
        return [
            ['uP'],
            ['uP💩'],
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
            ['up'],
            ['LO'],
            ['up💩'],
            ['LO💩'],
        ];
    }
}
