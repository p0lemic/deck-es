<?php

namespace Deck\Domain\Aggregate;

use Deck\Domain\Event\DomainEvent;

abstract class Aggregate implements AggregateRoot
{
    /** @var AggregateId */
    protected $id;
    /** @var DomainEvent[] */
    private $recordedEvents = [];

    public function getAggregateId(): AggregateId
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

    protected function applyAndRecordThat(DomainEvent $aDomainEvent): void
    {
        $this->recordThat($aDomainEvent);

        $this->apply($aDomainEvent);
    }

    protected function publishThat($domainEvent): void
    {
        //DomainEventPublisher::instance()->publish($domainEvent);
    }

    private function apply($anEvent): void
    {
    }
}
