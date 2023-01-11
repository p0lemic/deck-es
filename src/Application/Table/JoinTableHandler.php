<?php

namespace Deck\Application\Table;

use Deck\Application\Shared\Command\CommandInterface;
use Deck\Domain\Table\TableRepositoryInterface;
use Deck\Domain\User\PlayerReadModelRepositoryInterface;

class JoinTableHandler
{
    private TableRepositoryInterface $tableRepository;
    private PlayerReadModelRepositoryInterface $playerReadModelRepository;

    public function __construct(
        TableRepositoryInterface $tableRepository,
        PlayerReadModelRepositoryInterface $playerReadModelRepository
    ) {
        $this->tableRepository = $tableRepository;
        $this->playerReadModelRepository = $playerReadModelRepository;
    }

    public function handle(JoinTableCommand $joinTableCommand): void
    {
        $player = $this->playerReadModelRepository->findById($joinTableCommand->playerId());
        $table = $this->tableRepository->get($joinTableCommand->tableId());

        $table->playerSits($player->id());

        $this->tableRepository->store($table);
    }
}
