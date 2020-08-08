<?php

namespace Deck\Application\Game;

use Deck\Domain\Game\Game;
use Deck\Domain\Game\GameRepositoryInterface;

class LoadGame
{
    private GameRepositoryInterface $gameStore;

    public function __construct(
        GameRepositoryInterface $gameStore
    ) {
        $this->gameStore = $gameStore;
    }

    public function execute(LoadGameRequest $loadGameQuery): Game
    {
        return $this->gameStore->get($loadGameQuery->gameId());
    }
}
