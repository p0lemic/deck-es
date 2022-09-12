<?php

namespace Deck\Infrastructure\Bus;

use Deck\Application\Shared\Command\CommandBusInterface;
use Deck\Application\Shared\Command\CommandInterface;
use SimpleBus\SymfonyBridge\Bus\CommandBus;

class SimpleBusCommandBus implements CommandBusInterface
{
    private CommandBus $commandBus;

    public function __construct(
        CommandBus $commandBus
    ) {
        $this->commandBus = $commandBus;
    }

    public function handle(CommandInterface $command): void
    {
        $this->commandBus->handle($command);
    }
}
