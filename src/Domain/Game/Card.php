<?php

namespace Deck\Domain\Game;

use Broadway\EventSourcing\SimpleEventSourcedEntity;

class Card extends SimpleEventSourcedEntity
{
    public Rank $rank;
    public Suite $suite;

    public function __construct(Suite $suite, Rank $rank)
    {
        $this->suite = $suite;
        $this->rank = $rank;
    }

    public function suite(): Suite
    {
        return $this->suite;
    }

    public function rank(): Rank
    {
        return $this->rank;
    }

    public function points(): int
    {
        switch($this->rank->value()) {
            case "A":
                return 11;
            case "3":
                return 10;
            case "K":
                return 4;
            case "Q":
                return 3;
            case "J":
                return 2;
            default:
                return 0;
        }
    }

    public function equals(Card $card): bool
    {
        return $card->suite()->value() === $this->suite->value() && $card->rank()->value() === $this->rank->value();
    }

    public function __toString(): string
    {
        return $this->rank()->value() . $this->suite()->value();
    }
}
