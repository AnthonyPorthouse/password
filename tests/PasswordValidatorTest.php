<?php
declare(strict_types=1);

namespace Porthou\Password\Tests;

use Porthou\Password\PasswordException;
use Porthou\Password\PasswordValidator;
use Porthou\Password\Validators\PasswordBlacklistValidator;
use Porthou\Password\Validators\PasswordDomainValidator;
use Porthou\Password\Validators\PasswordLengthValidator;
use PHPUnit\Framework\TestCase;

class PasswordValidatorTest extends TestCase
{
    /** @var PasswordValidator $validator */
    private $validator;

    public function setUp()
    {
        $this->validator = new PasswordValidator([
            new PasswordLengthValidator(),
            new PasswordDomainValidator('groundsix.com'),
            new PasswordBlacklistValidator(),
        ]);
    }

    public function testAddingValidators(): void
    {
        $validator = new PasswordValidator();
        $this->assertCount(0, $validator->getValidators());
        $validator->addValidator(new PasswordLengthValidator());
        $this->assertCount(1, $validator->getValidators());
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
            ['asdasdaffasd'],
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
            ['2short'],
            ['groundsix'],
            ['password']
        ];
    }
}
