<?php

declare(strict_types=1);

namespace Deck\Infrastructure\User;

use Broadway\ReadModel\Projector;
use Deck\Domain\User\Event\UserWasCreated;
use Deck\Domain\User\Event\UserWasSignedIn;
use Deck\Domain\User\PlayerReadModel;
use Deck\Domain\User\PlayerReadModelRepositoryInterface;

class PlayerProjector extends Projector
{
    private PlayerReadModelRepositoryInterface $repository;

    public function __construct(PlayerReadModelRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function exposeStatusOfPlayer($playerId): PlayerReadModel
    {
        return $this->loadReadModel($playerId);
    }

    protected function applyUserWasCreated(UserWasCreated $userWasCreated): void
    {
        $playerReadModel = new PlayerReadModel(
            $userWasCreated->aggregateId(),
            $userWasCreated->credentials(),
            $userWasCreated->occurredOn()
        );

        $this->repository->save($playerReadModel);
    }

    protected function applyUserWasSignedIn(UserWasSignedIn $userWasSignedIn): void
    {
        /** @var PlayerReadModel $playerReadModel */
        $playerReadModel = $this->loadReadModel($userWasSignedIn->aggregateId());

        $playerReadModel->changeUpdatedAt($userWasSignedIn->occurredOn());

        $this->repository->save($playerReadModel);
    }

    private function loadReadModel($id)
    {
        return $this->repository->findById($id);
    }
}
