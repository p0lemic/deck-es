<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Events;

interface EventBus
{
    public function publish($singleEvent): void;

    public function publishEvents(array $events): void;
}
