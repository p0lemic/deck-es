<?php

namespace Deck\Application\Table;

use Deck\Domain\Table\Table;
use Deck\Domain\Table\TableId;
use Deck\Domain\Table\TableReadModel;
use Deck\Domain\Table\TableReadModelRepositoryInterface;

class GetTableQuery
{
    private TableReadModelRepositoryInterface $tableRepository;

    public function __construct(
        TableReadModelRepositoryInterface $tableRepository
    ) {
        $this->tableRepository = $tableRepository;
    }

    /**
     * @param TableId $id
     * @return TableReadModel|null
     */
    public function execute(TableId $id): ?TableReadModel
    {
        return $this->tableRepository->findByTableId($id);
    }
}
