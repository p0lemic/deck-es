<?php

namespace Deck\Domain\Deck;

use Deck\Domain\Aggregate\Aggregate;
use Deck\Domain\Deck\Exception\DeckCardsNumberException;

class Deck extends Aggregate
{
    public const TOTAL_INITIAL_CARDS_IN_DECK = 40;

    /** @var DeckId */
    private $id;
    /** @var Card[] */
    private $cards = [];

    /**
     * @param DeckId $aDeckId
     * @throws DeckCardsNumberException
     */
    public function __construct(DeckId $aDeckId)
    {
        $this->id = $aDeckId;

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

    public function id(): DeckId
    {
        return $this->id;
    }

    public function cards(): array
    {
        return $this->cards;
    }

    public function draw(): Card
    {
        return array_pop($this->cards);
    }
}
