<?php
declare(strict_types=1);

namespace Porthou\Password\Validators;

use Generator;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MessageFactory;
use Porthou\Password\PasswordException;

class PasswordPwnedApiValidator
{
    /** @var int $minimumThreshold */
    private $minimumThreshold;

    /** @var HttpClient */
    private $httpClient;

    /** @var MessageFactory */
    private $messageFactory;

    /**
     * PasswordPwnedApiValidator constructor.
     *
     * @param int $minimumThreshold How many times a password must appear before we consider it invalid
     *
     * @param HttpClient|null $httpClient The HTTP Client to use, or null for automatic discovery
     * @param MessageFactory|null $messageFactory The Message Factory to use or null for automatic discovery
     *
     * @see https://haveibeenpwned.com/API/v2 for information on the API.
     */
    public function __construct(int $minimumThreshold = 50, HttpClient $httpClient = null, MessageFactory $messageFactory = null)
    {
        $this->minimumThreshold = $minimumThreshold;

        if ($httpClient === null) {
            $this->httpClient = HttpClientDiscovery::find();
        } else {
            $this->httpClient = $httpClient;
        }

        if ($messageFactory === null) {
            $this->messageFactory = MessageFactoryDiscovery::find();
        } else {
            $this->messageFactory = $messageFactory;
        }
    }

    /** {@inheritdoc} */
    public function validate(string $password): bool
    {
        $passwordHash = sha1($password);
        $prefix = substr($passwordHash, 0, 5);

        foreach ($this->getBlacklistPasswords($prefix) as $badPassword) {
            [$badHash, $count] = $badPassword;
            if (
                $passwordHash === $prefix . $badHash
                && $count >= $this->minimumThreshold
            ) {
                throw new PasswordException('Password has been pwned.');
            }
        }

        return true;
    }

    /**
     * Iterates over and yields each blacklisted password
     *
     * @param string $partHash The first 5 characters of the sha1'd password
     * @return Generator
     */
    private function getBlacklistPasswords(string $partHash): Generator
    {
        try {
            $response = $this->httpClient->sendRequest(
                $this->messageFactory->createRequest(
                    'GET',
                    'https://api.pwnedpasswords.com/range/' . $partHash
                )
            );
        } catch (\Http\Client\Exception $e) {
            return;
        }

        $bodyStream = $response->getBody()->detach();

        while (($password = fgets($bodyStream)) !== false) {
            yield explode(':', trim($password));
        }

        fclose($bodyStream);
    }
}
