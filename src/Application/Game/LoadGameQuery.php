<?php

namespace Deck\Application\Game;

use Deck\Domain\Game\GameId;
use Deck\Domain\Game\GameReadModel;
use Deck\Domain\Game\GameReadModelRepositoryInterface;

class LoadGameQuery
{
    private GameReadModelRepositoryInterface $gameRepository;

    public function __construct(
        GameReadModelRepositoryInterface $gameRepository
    ) {
        $this->gameRepository = $gameRepository;
    }

    public function execute(GameId $gameId): GameReadModel
    {
        return $this->gameRepository->findByGameId($gameId);
    }
}
