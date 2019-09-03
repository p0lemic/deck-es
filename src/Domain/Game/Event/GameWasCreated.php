<?php

declare(strict_types=1);

namespace Deck\Domain\Game\Event;

use DateTimeImmutable;
use DateTimeInterface;
use Deck\Domain\Aggregate\AggregateId;
use Deck\Domain\Deck\Deck;
use Deck\Domain\Event\DomainEvent;
use Deck\Domain\User\Player;

final class GameWasCreated implements DomainEvent
{
    /** @var AggregateId */
    private $aggregateId;
    /** @var Deck */
    private $deck;
    /** @var Player[] */
    private $players;
    /** @var DateTimeInterface */
    private $occurredOn;

    public function __construct(
        AggregateId $id,
        Deck $deck,
        array $players
    ) {
        $this->aggregateId = $id;
        $this->deck = $deck;
        $this->players = $players;
        $this->occurredOn = new DateTimeImmutable();
    }

    public function deck(): Deck
    {
        return $this->deck;
    }

    public function players(): array
    {
        return $this->players;
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
        return $this->getAggregateId()->value()->toString();
    }
}
