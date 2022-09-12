<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Bus;

interface MessageBus
{
    public function handle($command);
}
