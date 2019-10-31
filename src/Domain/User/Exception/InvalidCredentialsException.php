<?php

declare(strict_types=1);

namespace Deck\Domain\User\Exception;

use Exception;

class InvalidCredentialsException extends Exception
{
    public static function invalid(): self
    {
        return new self('Invalid credentials entered.');
    }
}
