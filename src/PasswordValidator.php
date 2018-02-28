<?php
declare(strict_types=1);

namespace Porthou\Password;

class PasswordValidator
{
    /** @var Validator[] $validators */
    private $validators = [];

    public function __construct(iterable $validators = [])
    {
        foreach ($validators as $validator) {
            $this->addValidator($validator);
        }
    }

    /**
     * Adds a validator to the current PasswordValidator instance
     *
     * @param Validator $validator
     */
    public function addValidator(Validator $validator): void
    {
        $this->validators[] = $validator;
    }

    /**
     * Gets an array of the currently applied Validators
     *
     * @return Validator[]
     */
    public function getValidators(): array
    {
        return $this->validators;
    }

    /**
     * Validates the given password against the current rule set.
     *
     * @param string $password
     * @return bool True if the password is valid
     * @throws PasswordException If the password fails a validation attempt.
     */
    public function validate(string $password): bool
    {
        foreach ($this->validators as $validator) {
            $validator->validate($password);
        }

        return true;
    }
}
