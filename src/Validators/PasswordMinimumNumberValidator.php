<?php
declare(strict_types=1);

namespace Groundsix\Password\Validators;

use Groundsix\Password\PasswordException;
use Groundsix\Password\Validator;

class PasswordMinimumNumberValidator implements Validator
{
    /** @var int $minimum */
    private $minimum;

    /**
     * PasswordMinimumNumberValidator constructor.
     * @param int $minimum The minimum number of numbers that should be in the password
     */
    public function __construct(int $minimum = 1)
    {
        $this->minimum = $minimum;
    }

    /** {@inheritdoc} */
    public function validate(string $password): bool
    {
        $numbers = preg_match_all('/\d/', $password);

        if ($numbers >= $this->minimum) {
            return true;
        }

        throw new PasswordException('Password must contain at least ' . $this->minimum . ' numbers.');
    }
}