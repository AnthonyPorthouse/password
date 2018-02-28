<?php
declare(strict_types=1);

namespace Porthou\Password\Validators;

use Porthou\Password\PasswordException;
use Porthou\Password\Validator;

class PasswordSpecialCharacterValidator implements Validator
{
    /** @var string[] $whitelist */
    private $whitelist;
    /** @var int $minimum */
    private $minimum;

    /**
     * PasswordSpecialCharacterValidator constructor.
     * @param int $minimum The minimum number of special characters to require.
     * @param string[] $whitelist The whitelist of special characters to consider valid
     * @see https://www.owasp.org/index.php/Password_special_characters This is the default special character whitelist
     */
    public function __construct(int $minimum = 1, array $whitelist = [])
    {
        if (empty($whitelist)) {
            $whitelist = [
                ' ',
                '!',
                '"',
                '#',
                '$',
                '%',
                '&',
                '\'',
                '(',
                ')',
                '*',
                '+',
                ',',
                '-',
                '.',
                '/',
                ':',
                ';',
                '<',
                '=',
                '>',
                '?',
                '@',
                '[',
                '\\',
                ']',
                '^',
                '_',
                '`',
                '{',
                '|',
                '}',
                '~',

            ];
        }

        $this->minimum = $minimum;
        $this->whitelist = $whitelist;
    }

    /** {@inheritdoc} */
    public function validate(string $password): bool
    {
        $pattern = '/[\\' . implode('\\', $this->whitelist) . ']/';
        $count = preg_match_all($pattern, $password);

        if ($count >= $this->minimum) {
            return true;
        }

        throw new PasswordException('Password must include at least ' . $this->minimum . ' special characters.');
    }
}
