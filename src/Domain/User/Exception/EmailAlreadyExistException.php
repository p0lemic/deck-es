<?php

declare(strict_types=1);

namespace Deck\Domain\User\Exception;

use Deck\Domain\User\ValueObject\Email;
use Exception;
use InvalidArgumentException;

class EmailAlreadyExistException extends InvalidArgumentException
{
    public static function exists(Email $email): self
    {
        return new self(sprintf('Email %s already exists', $email->toString()));
    }
}
