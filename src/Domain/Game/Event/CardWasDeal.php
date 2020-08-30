<?php

declare(strict_types=1);

namespace Deck\Domain\Game\Event;

use DateTimeImmutable;
use Deck\Domain\Game\Card;
use Deck\Domain\Shared\AggregateId;
use Deck\Domain\Shared\ValueObject\DateTime;
use Deck\Domain\User\PlayerId;

class CardWasDeal
{
    private Card $card;
    private DateTime $occurredOn;
    private PlayerId $playerId;

    public function __construct(
        PlayerId $playerId,
        Card $card,
        DateTime $occurredOn
    ) {
        $this->playerId = $playerId;
        $this->card = $card;
        $this->occurredOn = $occurredOn;
    }

    public function playerId(): AggregateId
    {
        return $this->playerId;
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
