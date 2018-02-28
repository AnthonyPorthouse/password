<?php
declare(strict_types=1);

namespace Porthou\Password\Validators;

use Porthou\Password\PasswordException;
use Porthou\Password\Validator;

class PasswordLengthValidator implements Validator
{
    /** @var int $length */
    private $length;

    /**
     * PasswordLengthValidator constructor.
     *
     * @param int $length The minimum length the password must be
     */
    public function __construct(int $length = 8)
    {
        $this->length = $length;
    }

    /** {@inheritdoc} */
    public function validate(string $password): bool
    {
        if (mb_strlen($password) >= $this->length) {
            return true;
        }

        throw new PasswordException('Password is too short. It should be at least ' . $this->length . ' characters');
    }
}
