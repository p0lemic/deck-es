<?php

declare(strict_types=1);

namespace Deck\Domain\Deck\Event;

use DateTimeImmutable;
use DateTimeInterface;
use Deck\Domain\Aggregate\AggregateId;
use Deck\Domain\Deck\Card;
use Deck\Domain\Deck\Deck;
use Deck\Domain\Event\DomainEvent;

final class CardWasDrawn implements DomainEvent
{
    /** @var AggregateId */
    private $aggregateId;
    /** @var Card */
    private $card;
    /** @var Deck */
    private $deck;
    /** @var DateTimeInterface */
    private $occurredOn;

    public function __construct(
        Deck $deck,
        Card $card
    ) {
        $this->deck = $deck;
        $this->card = $card;
        $this->aggregateId = $deck->getAggregateId();
        $this->occurredOn = new DateTimeImmutable();
    }

    public function deck(): Deck
    {
        return $this->deck;
    }

    public function card(): Card
    {
        return $this->card;
    }

    public function getAggregateId(): AggregateId
    {
        return $this->aggregateId;
    }

    public function occurredOn(): DateTimeInterface
    {
        return $this->occurredOn;
    }

    public function __toString(): string
    {
        return $this->deck()->getAggregateId()->value()->toString();
    }
}
