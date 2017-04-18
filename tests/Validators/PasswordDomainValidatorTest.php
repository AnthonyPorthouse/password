<?php
declare(strict_types=1);

namespace Groundsix\Tests\Password\Validators;

use Groundsix\Password\PasswordException;
use Groundsix\Password\Validator;
use Groundsix\Password\Validators\PasswordDomainValidator;
use PHPUnit\Framework\TestCase;

class PasswordDomainValidatorTest extends TestCase
{
    /** @var Validator $validator */
    private $validator;

    public function setUp()
    {
        $this->validator = new PasswordDomainValidator('groundsix.com');
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
            ['groundsix'],
            ['groundsix.com'],
        ];
    }
}
