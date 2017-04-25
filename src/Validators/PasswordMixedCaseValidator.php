<?php
declare(strict_types=1);

namespace Groundsix\Password\Validators;

use Groundsix\Password\PasswordException;
use Groundsix\Password\Validator;

class PasswordMixedCaseValidator implements Validator
{
    /** {@inheritdoc} */
    public function validate(string $password): bool
    {
        if($password !== mb_strtolower($password) && $password !== mb_strtoupper($password)) {
            return true;
        }

        throw new PasswordException('Passwords must be a mixture of upper and lower case.');
    }
}