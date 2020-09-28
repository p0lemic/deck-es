<?php

declare(strict_types=1);

namespace Deck\Domain\Game\Exception;

use Exception;
use function sprintf;

class PlayerNotAllowedToDraw extends Exception
{
    public static function isFull(): self
    {
        return new self(
            sprintf('Players hand is full')
        );

    }
}
