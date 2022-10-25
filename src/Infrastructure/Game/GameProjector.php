<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Game;

use Broadway\ReadModel\Projector;
use Deck\Domain\Game\Event\CardWasAddedToDeck;
use Deck\Domain\Game\Event\CardWasDealt;
use Deck\Domain\Game\Event\CardWasDrawn;
use Deck\Domain\Game\Event\CardWasPlayed;
use Deck\Domain\Game\Event\GameWasCreated;
use Deck\Domain\Game\Event\HandWasWon;
use Deck\Domain\Game\GameId;
use Deck\Domain\Game\GameReadModel;
use Deck\Domain\Game\GameReadModelRepositoryInterface;
use Deck\Domain\Game\Player;

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
        $players = [];
        $currentPlayerId = null;

        foreach ($gameWasCreated->players() as $playerId) {
            $players[] = Player::create($playerId)->toArray();
            $currentPlayerId = $playerId;
        }

        $gameReadModel = new GameReadModel(
            $gameWasCreated->aggregateId()->value(),
            $players,
            $currentPlayerId?->value()
        );

        $this->repository->save($gameReadModel);
    }

    public function applyCardWasAddedToDeck(CardWasAddedToDeck $event): void
    {
        $gameReadModel = $this->loadReadModel($event->aggregateId);
        $gameReadModel->addCardToDeck($event->card()->normalize());

        $this->repository->update($gameReadModel);
    }

//    public function applyCardWasDrawn(CardWasDrawn $event): void
//    {
//        $gameReadModel = $this->loadReadModel($event->aggregateId);
//    }

    public function applyCardWasDealt(CardWasDealt $event): void
    {
        $gameReadModel = $this->loadReadModel($event->aggregateId);
        $gameReadModel->addCardToHand($event->playerId()->value(), $event->card()->normalize());

        $this->repository->update($gameReadModel);
    }

    public function applyCardWasPlayed(CardWasPlayed $event): void
    {
        $gameReadModel = $this->loadReadModel($event->aggregateId);
        $gameReadModel->playCard($event->playerId()->value(), $event->card()->normalize());

        $this->repository->update($gameReadModel);
    }
//
//    public function applyHandWasWon(HandWasWon $event): void
//    {
//        $gameReadModel = $this->loadReadModel($event->aggregateId);
//        $gameReadModel->wonCard($event->playerId(), $event->cards());
//
//        $this->repository->update($gameReadModel);
//    }

    private function loadReadModel(GameId $id): GameReadModel
    {
        return $this->repository->findByGameId($id);
    }
}
