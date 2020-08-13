<?php

namespace Deck\Application\Table;

use Deck\Domain\Table\Table;
use Deck\Domain\Table\TableRepositoryInterface;

class CreateTableHandler
{
    private TableRepositoryInterface $tableRepository;

    public function __construct(
        TableRepositoryInterface $tableRepository
    ) {
        $this->tableRepository = $tableRepository;
    }

    public function handle(CreateTableCommand $createTableCommand): void
    {
        $table = Table::create($createTableCommand->id());

        $this->tableRepository->store($table);
    }
}
