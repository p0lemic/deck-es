<?php

declare(strict_types=1);

namespace Deck\Domain\Game\Exception;

use Exception;
use function sprintf;

class InvalidPlayerNumberException extends Exception
{
    public static function biggerThanZero(): self
    {
        return new self(
            'Players have to be bigger than zero'
        );
    }

    public static function equalsToTwo(): self
    {
        return new self(
            'Players have to be two'
        );
    }

    public static function gameTableIsNotFull(): self
    {
        return new self(
            'The Table should have all seats filled by players'
        );
    }
}
