<?php

declare(strict_types=1);

namespace Deck\Domain\Deck\Event;

use DateTimeImmutable;
use DateTimeInterface;
use Deck\Domain\Aggregate\AggregateId;
use Deck\Domain\Deck\Card;
use Deck\Domain\Deck\Deck;
use Deck\Domain\Deck\DeckId;
use Deck\Domain\Event\DomainEvent;

class DeckWasCreated
{
    /** @var DeckId */
    private $deckId;
    /** @var DateTimeInterface */
    private $occurredOn;

    public function __construct(
        DeckId $deckId
    ) {
        $this->deckId = $deckId;
        $this->occurredOn = new DateTimeImmutable();
    }

    public function deckId(): DeckId
    {
        return $this->deckId;
    }

    public function occurredOn(): DateTimeInterface
    {
        return $this->occurredOn;
    }
}
