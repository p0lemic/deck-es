<?php

namespace Deck\Application\Game;

use Deck\Domain\Game\Game;
use Deck\Domain\Game\GameReadModelRepositoryInterface;

class GamesListQuery
{
    private GameReadModelRepositoryInterface $gameRepository;

    public function __construct(
        GameReadModelRepositoryInterface $gameRepository
    ) {
        $this->gameRepository = $gameRepository;
    }

    /**
     * @return Game[]
     */
    public function execute(): array
    {
        return $this->gameRepository->all();
    }
}
