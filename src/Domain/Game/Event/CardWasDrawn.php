<?php

declare(strict_types=1);

namespace Deck\Domain\Game\Event;

use DateTimeImmutable;
use DateTimeInterface;
use Deck\Domain\Game\Card;
use Deck\Domain\Shared\ValueObject\DateTime;

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

    public function occurredOn(): DateTime
    {
        return $this->occurredOn;
    }
}
