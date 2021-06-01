<?php

declare(strict_types=1);

namespace Deck\Domain\Game\Event;

use Deck\Domain\Shared\AggregateId;
use Deck\Domain\Shared\ValueObject\DateTime;
use Deck\Domain\User\PlayerId;

class HandWasWon
{
    private array $cards;
    private PlayerId $playerId;
    private DateTime $occurredOn;

    public function __construct(
        PlayerId $playerId,
        array $cards,
        DateTime $occurredOn
    ) {
        $this->playerId = $playerId;
        $this->cards = $cards;
        $this->occurredOn = $occurredOn;
    }

    public function playerId(): AggregateId
    {
        return $this->playerId;
    }

    public function cards(): array
    {
        return $this->cards;
    }

    public function occurredOn(): DateTime
    {
        return $this->occurredOn;
    }
}
