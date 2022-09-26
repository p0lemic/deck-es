<?php

declare(strict_types=1);

namespace Deck\Domain\Game\Exception;

use InvalidArgumentException;

class PlayerNotAllowedToDraw extends InvalidArgumentException
{
    public static function isFull(): self
    {
        return new self('Players hand is full');
    }
}
