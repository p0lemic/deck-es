<?php

namespace Deck\Application\Table;

use Deck\Domain\Table\Table;
use Deck\Domain\Table\TableRepositoryInterface;
use Deck\Domain\User\PlayerId;
use Deck\Domain\User\PlayerReadModelRepositoryInterface;
use function var_dump;

class CreateTableHandler
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

    public function handle(CreateTableCommand $createTableRCommand): void
    {
        $player = $this->playerReadModelRepository->findByIdOrFail(PlayerId::fromString($createTableRCommand->playerId()));

        $table = Table::create($player->id());

        $this->tableRepository->store($table);
    }
}
