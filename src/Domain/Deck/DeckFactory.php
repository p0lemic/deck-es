<?php

namespace Deck\Domain\Deck;

class DeckFactory
{
    public function createNew() : Deck
    {
        return new Deck(DeckId::create());
    }
}
