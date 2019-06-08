<?php

declare(strict_types=1);

namespace Deck\Domain\Deck\Exception;

use Exception;

class InvalidRankException extends Exception
{
    public static function fromRankString(string $aRank): self
    {
        $errorMessage = sprintf('Invalid rank type %s', $aRank);

        return new self($errorMessage);
    }
}
