<?php
declare(strict_types=1);

namespace Groundsix\Password\Tests;

use Groundsix\Password\PasswordValidator;
use Groundsix\Password\Validators\PasswordDomainValidator;
use Groundsix\Password\Validators\PasswordLengthValidator;
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

    public function validPasswords()
    {
        return [
            ['test1234'],
            ['💩💩💩💩💩💩💩💩'],
        ];
    }
}
