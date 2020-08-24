<?php

declare(strict_types=1);

namespace Deck\Domain\Table\Exception;

use Deck\Domain\Table\TableId;
use Exception;
use function sprintf;

class TableNotFoundException extends Exception
{
    public static function idNotFound(TableId $tableId): self
    {
        return new self(sprintf('Table with id %s not found', $tableId->value()));
    }
}
