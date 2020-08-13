<?php

declare(strict_types=1);

namespace Deck\Application\Table;

use Deck\Domain\Table\TableId;

final class CreateTableCommand
{
    private TableId $id;

    public function __construct()
    {
        $this->id = TableId::create();
    }

    public function id(): TableId
    {
        return $this->id;
    }
}
