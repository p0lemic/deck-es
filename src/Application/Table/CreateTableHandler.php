<?php

namespace Deck\Application\Table;

use Deck\Domain\Table\Table;
use Deck\Domain\Table\TableRepositoryInterface;

class CreateTableHandler
{
    /** @var TableRepositoryInterface */
    private $tableRepository;

    public function __construct(
        TableRepositoryInterface $tableRepository
    ) {
        $this->tableRepository = $tableRepository;
    }

    public function handle(CreateTableCommand $createTableRCommand): void
    {
        $table = Table::create();

        $this->tableRepository->store($table);
    }
}
