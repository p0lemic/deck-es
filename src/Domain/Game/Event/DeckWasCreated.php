<?php

declare(strict_types=1);

namespace Deck\Domain\Game\Event;

use DateTimeImmutable;
use DateTimeInterface;
use Deck\Domain\Game\Card;
use Deck\Domain\Game\DeckId;

class DeckWasCreated
{
    /** @var DeckId */
    private $deckId;
    /** @var Card[] */
    private $cards;
    /** @var DateTimeInterface */
    private $occurredOn;

    public function __construct(
        DeckId $deckId,
        array $cards
    ) {
        $this->deckId = $deckId;
        $this->cards = $cards;
        $this->occurredOn = new DateTimeImmutable();
    }

    public function deckId(): DeckId
    {
        return $this->deckId;
    }

    public function cards(): array
    {
        return $this->cards;
    }

    public function occurredOn(): DateTimeInterface
    {
        return $this->occurredOn;
    }
}
