<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Bus;

use Deck\Domain\Shared\DomainEvent;

interface EventBus
{
    public function publish(?DomainEvent $singleEvent): void;

    public function publishEvents(array $events): void;
}
