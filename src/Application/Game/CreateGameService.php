<?php

namespace Deck\Application\Game;

use Deck\Domain\Game\Game;
use Deck\Domain\Game\GameFactory;

class CreateGameService
{
    /** @var GameFactory */
    private $gameFactory;

    public function __construct(GameFactory $gameFactory)
    {
        $this->gameFactory = $gameFactory;
    }

    public function execute(CreateGameRequest $createGameRequest): Game
    {
        return $this->gameFactory->createNewGame($createGameRequest->players());
    }
}
