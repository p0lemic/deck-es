<?php

namespace Deck\Domain\Game;

class Deck
{
    /** @var Card[] */
    private $cards = [];

    public function __construct()
    {
        foreach (Suite::AVAILABLE_SUITES as $suite) {
            foreach (Rank::AVAILABLE_RANKS as $rank => $rankName) {
                $this->cards[] = new Card(new Suite($suite), new Rank($rank));
            }
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
