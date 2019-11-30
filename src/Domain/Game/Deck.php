<?php

namespace Deck\Domain\Game;

use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Deck\Domain\Game\Event\CardWasDrawn;
use Deck\Domain\Game\Event\DeckWasCreated;
use Deck\Domain\Game\Exception\DeckCardsNumberException;
use function array_pop;
use function count;
use function shuffle;

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

        $cards = [];

        foreach (Suite::AVAILABLE_SUITES as $suite) {
            foreach (Rank::AVAILABLE_RANKS as $rank => $rankName) {
                $cards[] = new Card(new Suite($suite), new Rank($rank));
            }
        }

        if (count($cards) !== self::TOTAL_INITIAL_CARDS_IN_DECK) {
            throw DeckCardsNumberException::invalidInitialNumber(self::TOTAL_INITIAL_CARDS_IN_DECK, count($cards));
        }

        shuffle($cards);

        $deck->apply(new DeckWasCreated($aDeckId, $cards));

        return $deck;
    }

    public static function createWithCards(DeckId $aDeckId, array $cards): self
    {
        $deck = new self();
        $deck->cards = $deck;

        return $deck;
    }

    public function applyDeckWasCreated(DeckWasCreated $event): void
    {
        $this->deckId = $event->deckId();
        $this->cards = $event->cards();
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
