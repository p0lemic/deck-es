<?php

declare(strict_types=1);

namespace Deck\Domain\Table;

use Deck\Domain\Shared\AggregateId;

interface TableRepositoryInterface
{
    public function get(AggregateId $id): Table;

    public function store(Table $table): void;
}
