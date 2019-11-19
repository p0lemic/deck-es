<?php

declare(strict_types=1);

namespace Deck\Domain\Deck\Event;

use DateTimeImmutable;
use DateTimeInterface;
use Deck\Domain\Aggregate\AggregateId;
use Deck\Domain\Deck\Card;
use Deck\Domain\Deck\Deck;
use Deck\Domain\Event\DomainEvent;

class CardWasDrawn
{
    /** @var Card */
    private $card;
    /** @var DateTimeInterface */
    private $occurredOn;

    public function __construct(
        Card $card
    ) {
        $this->card = $card;
        $this->occurredOn = new DateTimeImmutable();
    }

    public function card(): Card
    {
        return $this->card;
    }

    public function occurredOn(): DateTimeInterface
    {
        return $this->occurredOn;
    }
}
