<?php

namespace Deck\Domain\Game;

use Broadway\EventSourcing\SimpleEventSourcedEntity;

class Card extends SimpleEventSourcedEntity
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

    public function points(): int
    {
        return 1;
    }
}
