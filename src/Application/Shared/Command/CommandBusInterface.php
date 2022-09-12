<?php

declare(strict_types=1);

namespace Deck\Application\Shared\Command;

interface CommandBusInterface
{
    public function handle(CommandInterface $command);
}
