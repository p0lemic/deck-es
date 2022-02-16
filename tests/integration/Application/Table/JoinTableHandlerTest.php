<?php

namespace Deck\Tests\integration\Application\Table;

use Broadway\CommandHandling\CommandHandler;
use Broadway\CommandHandling\Testing\CommandHandlerScenarioTestCase;
use Broadway\EventHandling\EventBus;
use Broadway\EventStore\EventStore;
use Deck\Application\Table\JoinTableHandler;
use Deck\Infrastructure\Persistence\Repository\Table\EventSourcingTableStore;
use Deck\Infrastructure\Persistence\Repository\User\DoctrinePlayerReadModelRepository;
use Doctrine\Persistence\ManagerRegistry;

class JoinTableHandlerTest extends CommandHandlerScenarioTestCase
{
    protected function createCommandHandler(EventStore $eventStore, EventBus $eventBus): CommandHandler
    {
        $tableRepository = new EventSourcingTableStore($eventStore, $eventBus);
        $playerRepository = new DoctrinePlayerReadModelRepository(new ManagerRegistry());

        return new JoinTableHandler($tableRepository, $playerRepository);
    }
}
