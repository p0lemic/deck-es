<?php

declare(strict_types=1);

namespace Deck\Domain\Game\Event;

use DateTimeInterface;
use Deck\Domain\Game\Card;
use Deck\Domain\Shared\ValueObject\DateTime;

class CardWasAddedToDeck
{
    /** @var Card */
    private $card;
    /** @var DateTimeInterface */
    private $occurredOn;

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
