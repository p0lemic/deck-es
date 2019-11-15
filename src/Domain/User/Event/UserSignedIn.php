<?php

declare(strict_types=1);

namespace Deck\Domain\User\Event;

use DateTimeImmutable;
use DateTimeInterface;
use Deck\Domain\Aggregate\AggregateId;
use Deck\Domain\Event\DomainEvent;
use Deck\Domain\User\ValueObject\Email;

class UserSignedIn implements DomainEvent
{
    /** @var AggregateId */
    private $aggregateId;
    /** @var Email */
    private $email;
    /** @var DateTimeImmutable */
    private $occurredOn;

    public function __construct(
        AggregateId $id,
        Email $email
    ) {
        $this->aggregateId = $id;
        $this->email = $email;
        $this->occurredOn = new DateTimeImmutable();
    }

    public function aggregateId(): AggregateId
    {
        return $this->aggregateId;
    }

    public function email(): Email
    {
        return $this->email;
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
