<?php

namespace Deck\Application\Game;

use Deck\Domain\Game\Game;
use Deck\Domain\Game\GameFactory;

class CreateGameHandler
{
    /** @var GameFactory */
    private $gameFactory;

    public function __construct(GameFactory $gameFactory)
    {
        $this->gameFactory = $gameFactory;
    }

    public function handle(CreateGameCommand $createGameRequest): Game
    {
        return $this->gameFactory->createNewGame($createGameRequest->players());
    }
}
