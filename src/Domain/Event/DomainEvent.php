<?php

declare(strict_types=1);

namespace Deck\Domain\Event;

use Deck\Domain\Aggregate\AggregateId;

interface DomainEvent
{
    public function getAggregateId(): AggregateId;
}
