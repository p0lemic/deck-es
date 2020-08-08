<?php

declare(strict_types=1);

namespace Deck\Domain\Game\Event;

use DateTimeImmutable;
use DateTimeInterface;
use Deck\Domain\Game\Card;
use Deck\Domain\Shared\ValueObject\DateTime;

class CardWasDrawn
{
    private Card $card;
    private DateTime $occurredOn;

    public function __construct(
        Card $card,
        DateTime $occurredOn
    ) {
        $this->card = $card;
        $this->occurredOn = $occurredOn;
    }

    public function card(): Card
    {
        return $this->card;
    }

    public function occurredOn(): DateTime
    {
        return $this->occurredOn;
    }
}
