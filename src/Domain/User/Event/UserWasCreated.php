<?php

declare(strict_types=1);

namespace Deck\Domain\User\Event;

use DateTimeImmutable;
use DateTimeInterface;
use Deck\Domain\Aggregate\AggregateId;
use Deck\Domain\Event\DomainEvent;
use Deck\Domain\User\ValueObject\Auth\Credentials;

class UserWasCreated implements DomainEvent
{
    /** @var AggregateId */
    private $aggregateId;
    /** @var Credentials */
    private $credentials;
    /** @var DateTimeImmutable */
    private $occurredOn;

    public function __construct(
        AggregateId $id,
        Credentials $credentials
    ) {
        $this->aggregateId = $id;
        $this->credentials = $credentials;
        $this->occurredOn = new DateTimeImmutable();
    }

    public function aggregateId(): AggregateId
    {
        return $this->aggregateId;
    }

    public function credentials(): Credentials
    {
        return $this->credentials;
    }

    public function occurredOn(): DateTimeInterface
    {
        return $this->occurredOn;
    }

    public function __toString(): string
    {
        return $this->aggregateId()->value()->toString();
    }
}
