<?php

declare(strict_types=1);

namespace Deck\Application\Table;

use Deck\Domain\Table\TableId;

final class CreateTableCommand
{
    /** @var TableId */
    private $tableId;

    public function __construct(string $aTableId)
    {
        $this->tableId = TableId::fromString($aTableId);
    }

    public function tableId(): TableId
    {
        return $this->tableId;
    }
}
