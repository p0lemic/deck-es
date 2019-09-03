<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Events;

interface MessageBus
{
    public function handle($command);
}
