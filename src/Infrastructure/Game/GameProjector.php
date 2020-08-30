<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Game;

use Broadway\ReadModel\Projector;
use Deck\Domain\Game\Event\GameWasCreated;
use Deck\Domain\Game\Event\GameWasJoined;
use Deck\Domain\Game\Exception\InvalidPlayerNumber;
use Deck\Domain\Game\GameReadModel;
use Deck\Domain\Game\GameReadModelRepositoryInterface;
use function count;

class GameProjector extends Projector
{
    private GameReadModelRepositoryInterface $repository;

    public function __construct(GameReadModelRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function exposeStatusOfGame($gameId): GameReadModel
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

    private function loadReadModel($id)
    {
        return $this->repository->findByGameId($id);
    }
}
