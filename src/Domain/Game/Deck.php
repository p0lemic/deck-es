<?php

namespace Deck\Domain\Game;

use Broadway\EventSourcing\SimpleEventSourcedEntity;
use Deck\Domain\Game\Event\CardWasAddedToDeck;
use Deck\Domain\Game\Event\CardWasDrawn;
use function array_shift;

class Deck extends SimpleEventSourcedEntity
{
    public const TOTAL_INITIAL_CARDS_IN_DECK = 40;
    private DeckId $id;
    /** @var Card[] $cards */
    private array $cards;

    public function __construct(
        DeckId $aDeckId
    ) {
        $this->id = $aDeckId;
        $this->cards = [];
    }

    public static function create(
        DeckId $aDeckId
    ): self {
        return new self($aDeckId);
    }

    /** @return Card[]   */
    protected function getChildEntities(): array
    {
        return $this->cards;
    }

    public function id(): string
    {
        return $this->id->value();
    }

    public function cards(): array
    {
        return $this->cards;
    }

    public function getLastCard(): Card
    {
        return end($this->cards);
    }

    public function draw(): Card
    {
        return reset($this->cards);
    }

    public function applyCardWasAddedToDeck(CardWasAddedToDeck $event): void
    {
        $this->cards[] = $event->card();
    }

    public function applyCardWasDrawn(CardWasDrawn $event): void
    {
        array_shift($this->cards);
    }

    public function toArray(): array
    {
        return [
            'cards' => array_map(
                static fn (Card $card) => [$card->suite()->value(), $card->rank()->value()],
                $this->cards
            )
        ];
    }
}
