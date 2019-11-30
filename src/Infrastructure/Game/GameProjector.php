<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Game;

use Broadway\ReadModel\Projector;
use Deck\Application\Game\Exception\InvalidPlayerNumber;
use Deck\Domain\Game\Event\GameWasCreated;
use Deck\Domain\Game\Event\GameWasJoined;
use Deck\Domain\Game\GameReadModel;
use Deck\Domain\Game\GameReadModelRepositoryInterface;
use function count;

class GameProjector extends Projector
{
    private $repository;

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
            $gameWasCreated->deck(),
            $gameWasCreated->players()
        );

        $this->repository->save($gameReadModel);
    }

    public function applyGameWasJoined(GameWasJoined $gameWasJoined): void
    {
        /** @var GameReadModel $gameReadModel */
        $gameReadModel = $this->loadReadModel($gameWasJoined->aggregateId());

        $totalPlayers = count($gameReadModel->players());

        if ($totalPlayers >= 2) {
            throw InvalidPlayerNumber::gameIsFull();
        }

        $gameReadModel->joinPlayer($gameWasJoined->player());

        $this->repository->save($gameReadModel);
    }

    private function loadReadModel($id)
    {
        return $this->repository->findByGameId($id);
    }
}
