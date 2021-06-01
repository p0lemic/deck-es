<?php

declare(strict_types=1);

namespace Deck\Domain\Game\Exception;

use Exception;

class InvalidNumberOfWonCardsException extends Exception
{
    public static function notValid(): self
    {
        return new self('The number of won cards have to be 2');
    }
}
