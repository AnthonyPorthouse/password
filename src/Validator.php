<?php

declare(strict_types=1);

namespace Porthou\Password;

interface Validator
{
    /**
     * Validates the password against the current rule.
     *
     * @param string $password The password to verify.
     * @return bool True if the password passes. False otherwise.
     * @throws PasswordException
     */
    public function validate(string $password): bool;
}
