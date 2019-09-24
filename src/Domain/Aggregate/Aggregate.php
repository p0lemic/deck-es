<?php

namespace Deck\Domain\Aggregate;

use Deck\Domain\Event\DomainEvent;

abstract class Aggregate implements AggregateRoot
{
    /** @var AggregateId */
    protected $id;
    /** @var DomainEvent[] */
    private $recordedEvents = [];

    public function id(): AggregateId
    {
        return $this->id;
    }

    public function setAggregateId(AggregateId $id): void
    {
        $this->id = $id;
    }

    protected function recordThat(DomainEvent $aDomainEvent): void
    {
        $this->recordedEvents[] = $aDomainEvent;
    }

    public function getRecordedEvents(): array
    {
        return $this->recordedEvents;
    }

    public function clearRecordedEvents(): void
    {
        $this->recordedEvents = [];
    }

    public function hasChanges(): bool
    {
        // TODO: Implement hasChanges() method.
    }

    protected function recordThatAndApply(DomainEvent $aDomainEvent): void
    {
        $this->recordThat($aDomainEvent);
        $this->apply($aDomainEvent);
    }

    protected function publishThat($domainEvent): void
    {
        //DomainEventPublisher::instance()->publish($domainEvent);
    }

    protected function apply($anEvent): void
    {
        $classParts = explode('\\', get_class($anEvent));
        $method = 'apply'.end($classParts);

        $this->$method($anEvent);
    }
}
