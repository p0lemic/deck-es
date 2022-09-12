<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Game;

use Broadway\ReadModel\Projector;
use Deck\Domain\Game\Event\GameWasCreated;
use Deck\Domain\Game\GameId;
use Deck\Domain\Game\GameReadModel;
use Deck\Domain\Game\GameReadModelRepositoryInterface;

class GameProjector extends Projector
{
    private GameReadModelRepositoryInterface $repository;

    public function __construct(GameReadModelRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function exposeStatusOfGame(GameId $gameId): ?GameReadModel
    {
        return $this->loadReadModel($gameId);
    }

    public function applyGameWasCreated(GameWasCreated $gameWasCreated): void
    {
        $gameReadModel = new GameReadModel(
            $gameWasCreated->aggregateId(),
            $gameWasCreated->players()
        );

        $this->repository->save($gameReadModel);
    }

    private function loadReadModel(GameId $id): ?GameReadModel
    {
        return $this->repository->findByGameId($id);
    }
}
