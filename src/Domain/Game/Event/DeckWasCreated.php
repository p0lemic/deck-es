<?php

declare(strict_types=1);

namespace Deck\Domain\Game\Event;

use DateTimeImmutable;
use Deck\Domain\Game\DeckId;
use Deck\Domain\Shared\ValueObject\DateTime;

class DeckWasCreated
{
    private DeckId $aggregateId;
    private DateTime $occurredOn;

    public function __construct(
        DeckId $deckId,
        DateTime $occurredOn
    ) {
        $this->aggregateId = $deckId;
        $this->occurredOn = $occurredOn;
    }

    public function deckId(): DeckId
    {
        return $this->aggregateId;
    }

    public function occurredOn(): DateTime
    {
        return $this->occurredOn;
    }
}
