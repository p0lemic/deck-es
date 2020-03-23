<?php

namespace Deck\Application\Table;

use Deck\Domain\Table\TableRepositoryInterface;
use Deck\Domain\User\PlayerReadModelRepositoryInterface;

class PlayerSitOnTableHandler
{
    /** @var TableRepositoryInterface */
    private $tableRepository;
    /** @var PlayerReadModelRepositoryInterface */
    private $playerReadModelRepository;

    public function __construct(
        TableRepositoryInterface $tableRepository,
        PlayerReadModelRepositoryInterface $playerReadModelRepository
    ) {
        $this->tableRepository = $tableRepository;
        $this->playerReadModelRepository = $playerReadModelRepository;
    }

    public function handle(PlayerSitOnTableCommand $playerSitOnTableCommand): void
    {
        $player = $this->playerReadModelRepository->findByIdOrFail($playerSitOnTableCommand->playerId());
        $table = $this->tableRepository->get($playerSitOnTableCommand->tableId());

        $table->playerSits($player->id());
        $this->tableRepository->store($table);

        if ($table->isFull()) {

        }
    }
}
