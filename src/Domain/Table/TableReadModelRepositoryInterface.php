<?php

declare(strict_types=1);

namespace Deck\Domain\Table;

interface TableReadModelRepositoryInterface
{
    /**
     * @param TableId $tableId
     * @return TableReadModel
     */
    public function findByTableId(TableId $tableId): ?TableReadModel;

    public function findByTableIdOrFail(TableId $tableId): TableReadModel;

    public function all(): array;

    /**
     * @param TableReadModel $table
     */
    public function save(TableReadModel $table): void;

    /**
     * @return void
     */
    public function clearMemory(): void;
}
