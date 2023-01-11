<?php

declare(strict_types=1);

namespace Deck\Domain\Table\Exception;

use Deck\Domain\User\PlayerId;
use InvalidArgumentException;

class PlayerAlreadyInTableException extends InvalidArgumentException
{
    public static function alreadyInTable(PlayerId $playerId): self
    {
        $errorMessage = sprintf('Player %s is already in this table', $playerId->value());

        return new self($errorMessage);
    }
}
