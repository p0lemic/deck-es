<?php

declare(strict_types=1);

namespace Deck\Domain\Game\Exception;

use Exception;
use function sprintf;

class CardPlayedNotInPlayerHand extends Exception
{
    public static function notFound(): self
    {
        return new self(
            sprintf('Card played not found in player hand')
        );
    }
}
