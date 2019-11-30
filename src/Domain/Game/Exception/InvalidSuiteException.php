<?php

declare(strict_types=1);

namespace Deck\Domain\Game\Exception;

use Exception;

class InvalidSuiteException extends Exception
{
    public static function fromSuiteString(string $aSuite): self
    {
        $errorMessage = sprintf('Invalid suite type %s', $aSuite);

        return new self($errorMessage);
    }
}
