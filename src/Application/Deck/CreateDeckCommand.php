<?php

declare(strict_types=1);

namespace Deck\Application\Deck;

use Deck\Domain\Deck\DeckId;

class CreateDeckCommand
{
    /** @var DeckId */
    private $deckId;

    public function __construct(DeckId $deckId)
    {
        $this->deckId = $deckId;
    }

    public function deckId(): DeckId
    {
        return $this->deckId;
    }
}
