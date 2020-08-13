<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Table;

use Broadway\ReadModel\Projector;
use Deck\Domain\Table\Event\PlayerWasLeaved;
use Deck\Domain\Table\Event\PlayerWasSeated;
use Deck\Domain\Table\Event\TableWasCreated;
use Deck\Domain\Table\TableReadModel;
use Deck\Domain\Table\TableReadModelRepositoryInterface;

class TableProjector extends Projector
{
    private TableReadModelRepositoryInterface $repository;

    public function __construct(TableReadModelRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function exposeStatusOfTable($tableId): TableReadModel
    {
        return $this->loadReadModel($tableId);
    }

    public function applyTableWasCreated(TableWasCreated $tableWasCreated): void
    {
        $tableReadModel = new TableReadModel(
            $tableWasCreated->aggregateId()
        );

        $this->repository->save($tableReadModel);
    }

    public function applyPlayerWasLeaved(PlayerWasLeaved $event): void
    {
        /** @var TableReadModel $tableReadModel */
        $tableReadModel = $this->loadReadModel($event->aggregateId());
        $tableReadModel->removePlayer($event->playerId());

        $this->repository->save($tableReadModel);
    }

    public function applyPlayerWasSeated(PlayerWasSeated $playerWasSeated): void
    {
        /** @var TableReadModel $tableReadModel */
        $tableReadModel = $this->loadReadModel($playerWasSeated->aggregateId());
        $tableReadModel->joinPlayer($playerWasSeated->playerId());

        $this->repository->save($tableReadModel);
    }

    private function loadReadModel($id)
    {
        return $this->repository->findByTableId($id);
    }
}
