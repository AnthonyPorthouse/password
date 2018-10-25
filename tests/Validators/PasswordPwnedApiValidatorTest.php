<?php
declare(strict_types=1);

namespace Porthou\Password\Tests\Validators;

use Http\Discovery\MessageFactoryDiscovery;
use Http\Mock\Client;
use Porthou\Password\PasswordException;
use Porthou\Password\Validators\PasswordPwnedApiValidator;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class PasswordPwnedApiValidatorTest extends TestCase
{
    /** @var PasswordPwnedApiValidator $validator */
    private $validator;

    /** @var Client */
    private $client;

    public function setUp(): void
    {
        $this->client = new Client();

        $this->client->setDefaultResponse($this->createResponse());

        $this->validator = new PasswordPwnedApiValidator(1, $this->client);
    }

    /**
     * @throws PasswordException
     */
    public function testPwnedCount(): void
    {
        $validator = new PasswordPwnedApiValidator(10, $this->client);
        $this->client->setDefaultResponse($this->createResponse());
        $this->assertTrue($validator->validate('password'));

        $validator = new PasswordPwnedApiValidator(5, $this->client);
        $this->expectException(PasswordException::class);
        $this->client->setDefaultResponse($this->createResponse());
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

    /**
     * @return ResponseInterface
     */
    private function createResponse(): ResponseInterface
    {
        $fh = fopen(__DIR__ . '/assets/apipwndlist.txt', 'rb');

        if (!$fh) {
            throw new \RuntimeException('Example file cannot be opened');
        }

        return MessageFactoryDiscovery::find()->createResponse(
            200,
            null,
            [],
            $fh
        );
    }
}
