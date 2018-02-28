<?php
/**
 * Created by PhpStorm.
 * User: port3
 * Date: 25/04/2017
 * Time: 08:53
 */

namespace Porthou\Password\Tests\Validators;

use Porthou\Password\PasswordException;
use Porthou\Password\Validators\PasswordSpecialCharacterValidator;
use PHPUnit\Framework\TestCase;

class PasswordSpecialCharacterValidatorTest extends TestCase
{
    /** @var PasswordSpecialCharacterValidator $validator */
    private $validator;

    public function setUp()
    {
        $this->validator = new PasswordSpecialCharacterValidator();
    }

    public function testCustomConfig(): void
    {
        $validator = new PasswordSpecialCharacterValidator(2, ['*']);
        $this->assertTrue($validator->validate('test**'));
        $this->expectException(PasswordException::class);
        $validator->validate('test*^');
        $this->expectException(PasswordException::class);
        $validator->validate('test^');
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
            ['password^'],
            ['password^['],
            ['$'],
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
            ['test1234'],
        ];
    }
}
