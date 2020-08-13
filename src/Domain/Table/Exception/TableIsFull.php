<?php

declare(strict_types=1);

namespace Deck\Domain\Table\Exception;

use Deck\Domain\User\PlayerId;
use Exception;

class TableIsFull extends Exception
{
    public static function isFull(PlayerId $playerId): self
    {
        $errorMessage = sprintf('Player %s can\'t be added because table is full', $playerId->value());

        return new self($errorMessage);
    }
}
