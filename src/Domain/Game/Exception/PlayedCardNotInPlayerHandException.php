<?php

declare(strict_types=1);

namespace Deck\Domain\Game\Exception;

use Exception;
use function sprintf;

class PlayedCardNotInPlayerHandException extends Exception
{
    public static function notFound(): self
    {
        return new self(
            'Card played not found in player hand'
        );
    }
}
