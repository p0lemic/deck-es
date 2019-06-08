<?php

declare(strict_types=1);

namespace Deck\Domain\Game\Exception;

use Exception;

class CardsNumberInUseNotValidException extends Exception
{
    public static function invalidNumber(int $expectedTotalCards, int $currentTotalCards): self
    {
        $errorMessage = sprintf('Invalid number of cards in game: %d, expected %d', $currentTotalCards, $expectedTotalCards);

        return new self($errorMessage);
    }
}
