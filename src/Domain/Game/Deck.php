<?php

namespace Deck\Domain\Game;

use Broadway\EventSourcing\SimpleEventSourcedEntity;
use Deck\Domain\Game\Event\CardWasAddedToDeck;
use Deck\Domain\Game\Event\CardWasDrawn;
use Deck\Domain\Game\Exception\DeckCardsNumberException;
use Deck\Domain\Shared\ValueObject\DateTime;
use function array_flip;
use function array_pop;
use function array_reverse;
use function array_shift;
use function count;
use function shuffle;

class Deck extends SimpleEventSourcedEntity
{
    public const TOTAL_INITIAL_CARDS_IN_DECK = 40;
    private DeckId $id;
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

    public function shuffleCards(): void
    {
        $cards = [];

        foreach (Suite::AVAILABLE_SUITES as $suite) {
            foreach (Rank::AVAILABLE_RANKS as $rank) {
                $cards[] = new Card(new Suite($suite), new Rank($rank));
            }
        }

        if (count($cards) !== self::TOTAL_INITIAL_CARDS_IN_DECK) {
            throw DeckCardsNumberException::invalidInitialNumber(self::TOTAL_INITIAL_CARDS_IN_DECK, count($cards));
        }

        shuffle($cards);

        foreach ($cards as $card) {
            $this->apply(new CardWasAddedToDeck($card, DateTime::now()));
        }
    }

    public function applyCardWasAddedToDeck(CardWasAddedToDeck $event): void
    {
        $this->cards[] = $event->card();
    }

    protected function getChildEntities(): array
    {
        return $this->cards;
    }

    public function draw(): Card
    {
        $card = reset($this->cards);
        $this->apply(new CardWasDrawn($card, DateTime::now()));

        return $card;
    }

    public function applyCardWasDrawn(CardWasDrawn $event): void
    {
        array_shift($this->cards);
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
}
