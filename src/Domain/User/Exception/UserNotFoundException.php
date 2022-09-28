<?php

declare(strict_types=1);

namespace Deck\Domain\User\Exception;

use Deck\Domain\User\PlayerId;
use Deck\Domain\User\ValueObject\Email;
use InvalidArgumentException;
use function sprintf;

class UserNotFoundException extends InvalidArgumentException
{
    public static function idNotFound(PlayerId $playerId): self
    {
        return new self(sprintf('User with id %s not found', $playerId->value()));
    }

    public static function emailNotFound(Email $email): self
    {
        return new self(sprintf('User with email %s not found', $email->toString()));
    }
}
