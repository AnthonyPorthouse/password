<?php
declare(strict_types=1);

namespace Groundsix\Password\Tests\Validators;

use Groundsix\Password\PasswordException;
use Groundsix\Password\Validators\PasswordMinimumNumberValidator;
use PHPUnit\Framework\TestCase;

class PasswordMinimumNumberValidatorTest extends TestCase
{
    /** @var PasswordMinimumNumberValidator $validator */
    private $validator;

    public function setUp()
    {
        $this->validator = new PasswordMinimumNumberValidator();
    }

    public function testCustomNumberAmount(): void
    {
        $validator = new PasswordMinimumNumberValidator(2);
        $this->assertTrue($validator->validate('password12'));
        $this->expectException(PasswordException::class);
        $validator->validate('password1');
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
            ['a1'],
            ['a12'],
            ['1'],
            ['12'],
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
            ['password'],
            ['invalid'],
            [''],
        ];
    }
}
