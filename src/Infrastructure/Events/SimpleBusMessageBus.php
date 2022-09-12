<?php

namespace Deck\Infrastructure\Events;

use SimpleBus\SymfonyBridge\Bus\CommandBus;

class SimpleBusMessageBus implements MessageBus
{
    private CommandBus $commandBus;

    public function __construct(
        CommandBus $commandBus
    ) {
        $this->commandBus = $commandBus;
    }

    public function handle($command): void
    {
        $this->commandBus->handle($command);
    }
}
