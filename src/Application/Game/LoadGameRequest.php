<?php

declare(strict_types=1);

namespace Deck\Application\Game;

use Deck\Domain\Game\GameId;

class LoadGameRequest
{
    /** @var GameId */
    private $gameId;

    public function __construct(string $aGameId)
    {
        $this->gameId = GameId::fromString($aGameId);
    }

    public function gameId(): GameId
    {
        return $this->gameId;
    }
}
