<?php

declare(strict_types=1);

namespace Deck\Domain\Event;

use DateTimeInterface;
use Deck\Domain\Aggregate\AggregateId;

interface DomainEvent
{
    public function aggregateId(): AggregateId;
    public function occurredOn(): DateTimeInterface;
}
