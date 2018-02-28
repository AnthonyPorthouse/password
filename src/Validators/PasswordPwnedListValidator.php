<?php
declare(strict_types=1);

namespace Porthou\Password\Validators;

use Generator;
use Porthou\Password\PasswordException;

class PasswordPwnedListValidator
{
    /** @var string $file */
    private $file;

    /** @var int $minimumThreshold */
    private $minimumThreshold;

    /**
     * PasswordPwnedListValidator constructor.
     *
     * @param string $file the path to the blacklist file
     * @param int $minimumThreshold How many times a password must appear before we consider it invalid
     * @see https://haveibeenpwned.com/Passwords for access to the list of passwords to be used with this validator.
     */
    public function __construct(string $file, $minimumThreshold = 50)
    {
        $this->file = $file;
        $this->minimumThreshold = $minimumThreshold;
    }

    /** {@inheritdoc} */
    public function validate(string $password): bool
    {
        $passwordHash = sha1($password);

        foreach ($this->getBlacklistPasswords() as $badPassword) {
            [$badHash, $count] = $badPassword;
            if (
                $passwordHash === $badHash
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
     * @return Generator
     */
    private function getBlacklistPasswords(): Generator
    {
        $fh = fopen($this->file, 'rb');

        while (($password = fgets($fh)) !== false) {
            yield explode(':', trim($password));
        }

        fclose($fh);
    }
}
