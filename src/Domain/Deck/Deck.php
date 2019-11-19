<?php

namespace Deck\Domain\Deck;

use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Deck\Domain\Deck\Event\CardWasDrawn;
use Deck\Domain\Deck\Event\DeckWasCreated;
use Deck\Domain\Deck\Exception\DeckCardsNumberException;
use function array_pop;

class Deck extends EventSourcedAggregateRoot
{
    public const TOTAL_INITIAL_CARDS_IN_DECK = 40;

    /** @var DeckId */
    private $deckId;
    /** @var Card[] */
    private $cards = [];

    public static function create(DeckId $aDeckId): self
    {
        $deck = new self();

        $deck->apply(new DeckWasCreated($aDeckId));

        return $deck;
    }

    public function applyDeckWasCreated(DeckWasCreated $event): void
    {
        $this->deckId = $event->deckId();

        foreach (Suite::AVAILABLE_SUITES as $suite) {
            foreach (Rank::AVAILABLE_RANKS as $rank => $rankName) {
                $this->cards[] = new Card(new Suite($suite), new Rank($rank));
            }
        }

        if (count($this->cards) !== self::TOTAL_INITIAL_CARDS_IN_DECK) {
            throw DeckCardsNumberException::invalidInitialNumber(self::TOTAL_INITIAL_CARDS_IN_DECK, count($this->cards));
        }

        shuffle($this->cards);
    }

    protected function getChildEntities(): array
    {
        return $this->cards;
    }

    public function draw(): Card
    {
        $card = reset($this->cards);
        $this->apply(new CardWasDrawn($card));

        return $card;
    }

    public function applyCardWasDrawn(CardWasDrawn $event): void
    {
        array_pop($this->cards);
    }

    public function getAggregateRootId(): string
    {
        return $this->deckId->value();
    }

    public function cards(): array
    {
        return $this->cards;
    }
}
