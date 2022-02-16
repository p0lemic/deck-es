<?php

namespace Deck\Application\Table;

use Broadway\CommandHandling\CommandHandler;
use Deck\Domain\Table\TableRepositoryInterface;
use Deck\Domain\User\PlayerReadModelRepositoryInterface;

class JoinTableHandler implements CommandHandler
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

    public function handle($joinTableCommand): void
    {
        $player = $this->playerReadModelRepository->findByIdOrFail($joinTableCommand->playerId());
        $table = $this->tableRepository->get($joinTableCommand->tableId());

        $table->playerSits($player->id());

        $this->tableRepository->store($table);
    }
}
