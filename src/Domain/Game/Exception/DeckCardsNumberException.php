<?php

declare(strict_types=1);

namespace Deck\Domain\Game\Exception;

use Exception;

class DeckCardsNumberException extends Exception
{
    public static function invalidInitialNumber(int $expectedTotalCards, int $currentTotalCards): self
    {
        $errorMessage = sprintf('Invalid total number of cards: %d, expected %d', $currentTotalCards, $expectedTotalCards);

        return new self($errorMessage);
    }
}
