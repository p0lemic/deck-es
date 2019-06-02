<?php

declare(strict_types=1);

namespace Deck\Application\Game\Exception;

use Exception;

class InvalidPlayerNumber extends Exception
{
    public static function biggerThanZero(): self
    {
        return new self(
            sprintf('Player have to be bigger than zero')
        );

    }
}
