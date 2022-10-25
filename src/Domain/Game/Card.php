<?php

namespace Deck\Domain\Game;

use Broadway\EventSourcing\SimpleEventSourcedEntity;

class Card extends SimpleEventSourcedEntity
{
    public Rank $rank;
    public Suite $suite;

    public function __construct(
        Suite $suite,
        Rank $rank
    ) {
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
        return match ($this->rank->value()) {
            "A" => 11,
            "3" => 10,
            "K" => 4,
            "Q" => 3,
            "J" => 2,
            default => 0,
        };
    }

    public function equals(Card $card): bool
    {
        return $card->suite()->value() === $this->suite->value() && $card->rank()->value() === $this->rank->value();
    }

    public function __toString(): string
    {
        return $this->rank()->value() . $this->suite()->value();
    }

    public function normalize(): array
    {
        return [
            'suite' => $this->suite->value(),
            'rank' => $this->rank->value(),
        ];
    }
}
