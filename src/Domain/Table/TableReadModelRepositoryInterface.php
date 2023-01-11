<?php

declare(strict_types=1);

namespace Deck\Domain\Table;

interface TableReadModelRepositoryInterface
{
    public function findByTableId(TableId $tableId): TableReadModel;
    public function all(): array;
    public function save(TableReadModel $table): void;
    public function update(TableReadModel $table): void;
}
