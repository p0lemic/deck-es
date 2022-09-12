<?php

declare(strict_types=1);

namespace Deck\Application\Game;

use Deck\Application\Shared\Command\CommandInterface;
use Deck\Domain\Game\GameId;
use Deck\Domain\User\PlayerId;

final class DrawCardCommand implements CommandInterface
{
    private GameId $gameId;
    private PlayerId $playerId;

    public function __construct(
        string $gameId,
        string $playerId
    ) {
        $this->gameId = GameId::fromString($gameId);
        $this->playerId = PlayerId::fromString($playerId);
    }

    public function gameId(): GameId
    {
        return $this->gameId;
    }

    public function playerId(): PlayerId
    {
        return $this->playerId;
    }
}
