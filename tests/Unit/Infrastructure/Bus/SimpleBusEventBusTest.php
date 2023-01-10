<?php

namespace Deck\Tests\Unit\Infrastructure\Bus;

use Deck\Domain\Shared\ValueObject\DateTime;
use Deck\Domain\Table\Event\TableWasCreated;
use Deck\Domain\Table\TableId;
use Deck\Infrastructure\Bus\SimpleBusEventBus;
use PHPUnit\Framework\TestCase;
use SimpleBus\SymfonyBridge\Bus\EventBus;

class SimpleBusEventBusTest extends TestCase
{
    private readonly EventBus $eventBus;
    private readonly SimpleBusEventBus $sut;

    public function setUp(): void
    {
        $this->eventBus = $this->createMock(EventBus::class);
        $this->sut = new SimpleBusEventBus($this->eventBus);
    }

    public function testPublishEventsToTheEventBus(): void
    {
        $this->eventBus->expects($this->exactly(3))
            ->method('handle');

        $this->sut->publishEvents(
            [
                new TableWasCreated(TableId::create(), DateTime::now()),
                new TableWasCreated(TableId::create(), DateTime::now()),
                null,
                new TableWasCreated(TableId::create(), DateTime::now()),
            ]
        );
    }
}
