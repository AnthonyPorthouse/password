<?php
declare(strict_types=1);

namespace Groundsix\Password\Validators;

use Groundsix\Password\PasswordException;
use Groundsix\Password\Validator;

class PasswordDomainValidator implements Validator
{
    /** @var string $domain */
    private $domain;

    /**
     * PasswordDomainValidator constructor.
     * @param string $domain The domain to check that the password isn't
     */
    public function __construct(string $domain)
    {
        $this->domain = $domain;
    }

    /** {@inheritdoc} */
    public function validate(string $password): bool
    {
        if (mb_strpos($this->domain, $password) === false) {
            return true;
        }

        throw new PasswordException('Password is must not contain the current domain name.');
    }
}
