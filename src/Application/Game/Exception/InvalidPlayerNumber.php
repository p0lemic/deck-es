<?php

declare(strict_types=1);

namespace Deck\Application\Game\Exception;

use Exception;
use function sprintf;

class InvalidPlayerNumber extends Exception
{
    public static function biggerThanZero(): self
    {
        return new self(
            sprintf('Players have to be bigger than zero')
        );

    }

    public static function equalsToTwo(): self
    {
        return new self(
            sprintf('Players have to be two')
        );

    }

    public static function gameIsFull(): self
    {
        return new self(
            sprintf('The Game is full')
        );

    }
}
