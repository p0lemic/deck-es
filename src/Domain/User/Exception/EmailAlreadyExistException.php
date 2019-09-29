<?php

declare(strict_types=1);

namespace Deck\Domain\User\Exception;

use Deck\Domain\User\ValueObject\Email;
use Exception;

class EmailAlreadyExistException extends Exception
{
    public static function exists(Email $email): self
    {
        $errorMessage = sprintf('Email %s already exists', $email);

        return new self($errorMessage);
    }
}
