<?php

namespace Deck\Domain\Deck;

class Card
{
    /** @var Rank */
    public $rank;
    /** @var Suite */
    public $suite;

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

    public function __toString(): string
    {
        return $this->rank->value() . ' => ' . $this->suite->value();
    }
}
