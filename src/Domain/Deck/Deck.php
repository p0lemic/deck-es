<?php

namespace Deck\Domain\Deck;

use Deck\Domain\Deck\Exception\DeckCardsNumberException;

class Deck
{
    public const TOTAL_INITIAL_CARDS_IN_DECK = 40;

    /** @var Card[] */
    private $cards = [];

    public function __construct()
    {
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

    public function cards(): array
    {
        return $this->cards;
    }

    public function draw(): Card
    {
        return array_pop($this->cards);
    }
}
