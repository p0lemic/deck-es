<?php

declare(strict_types=1);

namespace Deck\Domain\User\Exception;

use Exception;
use InvalidArgumentException;

class InvalidCredentialsException extends InvalidArgumentException
{
    public static function invalid(): self
    {
        return new self('Invalid credentials entered.');
    }
}
