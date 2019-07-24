<?php

declare(strict_types=1);

namespace Deck\Domain\Aggregate;

use Deck\Domain\Event\DomainEvent;

interface AggregateRoot
{
    /** @return DomainEvent[] */
    public function getRecordedEvents(): array;

    public function clearRecordedEvents(): void;

    public function hasChanges(): bool;
}
