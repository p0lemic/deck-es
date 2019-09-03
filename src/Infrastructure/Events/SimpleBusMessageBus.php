<?php

namespace Deck\Infrastructure\Events;

use SimpleBus\SymfonyBridge\Bus\CommandBus;

class SimpleBusMessageBus implements MessageBus
{
    /** @var CommandBus */
    private $commandBus;

    /**
     * EventDispatcher constructor.
     * @param CommandBus $commandBus
     */
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
