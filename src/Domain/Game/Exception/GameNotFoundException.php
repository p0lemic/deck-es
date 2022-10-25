<?php

declare(strict_types=1);

namespace Deck\Domain\Game\Exception;

use Deck\Domain\Game\GameId;
use Exception;
use function sprintf;

class GameNotFoundException extends Exception
{
    public static function idNotFound(GameId $gameId): self
    {
        return new self(sprintf('Game with id %s not found', $gameId->value()));
    }
}
