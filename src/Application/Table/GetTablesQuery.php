<?php

namespace Deck\Application\Table;

use Deck\Domain\Table\TableReadModel;
use Deck\Domain\Table\TableReadModelRepositoryInterface;

class GetTablesQuery
{
    private TableReadModelRepositoryInterface $tableRepository;

    public function __construct(
        TableReadModelRepositoryInterface $tableRepository
    ) {
        $this->tableRepository = $tableRepository;
    }

    /**
     * @return TableReadModel[]
     */
    public function execute(): array
    {
        return $this->tableRepository->all();
    }
}
