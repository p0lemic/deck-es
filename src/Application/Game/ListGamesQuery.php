<?php

namespace Deck\Application\Game;

use Deck\Domain\Game\GameReadModel;
use Deck\Domain\Game\GameReadModelRepositoryInterface;

class ListGamesQuery
{
    private GameReadModelRepositoryInterface $gameRepository;

    public function __construct(
        GameReadModelRepositoryInterface $gameRepository
    ) {
        $this->gameRepository = $gameRepository;
    }

    /** @return GameReadModel[] */
    public function execute(): array
    {
        return $this->gameRepository->all();
    }
}
