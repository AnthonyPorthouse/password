<?php
declare(strict_types=1);

namespace Porthou\Password\Validators;

use Generator;
use Porthou\Password\PasswordException;
use Porthou\Password\Validator;

class PasswordBlacklistValidator implements Validator
{
    /** @var string $file */
    private $file;

    public function __construct(string $file = null)
    {
        if ($file !== null) {
            $this->file = $file;
        } else {
            $this->file = __DIR__ . '/../../res/top_10000.txt';
        }
    }

    /** {@inheritdoc} */
    public function validate(string $password): bool
    {
        foreach ($this->getBlacklistPasswords() as $badPassword) {
            if ($password === $badPassword) {
                throw new PasswordException('Password is blacklisted.');
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
        $fh = @fopen($this->file, 'rb');

        if ($fh === false) {
            return;
        }

        while (($password = fgets($fh)) !== false) {
            yield trim((string)$password);
        }

        fclose($fh);
    }
}
