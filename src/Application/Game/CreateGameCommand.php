<?php

declare(strict_types=1);

namespace Deck\Application\Game;

use Deck\Domain\Game\GameId;
use Deck\Domain\User\Player;

final class CreateGameCommand
{
    /** @var array */
    private $players;
    /** @var GameId */
    private $gameId;

    public function __construct(string $aGameId, array $players)
    {
        $this->gameId = GameId::fromString($aGameId);
        $this->players = $players;
    }

    public function gameId(): GameId
    {
        return $this->gameId;
    }

    public function players(): array
    {
        return $this->players;
    }
}
