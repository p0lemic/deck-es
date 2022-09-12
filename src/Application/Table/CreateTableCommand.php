<?php

declare(strict_types=1);

namespace Deck\Application\Table;

use Deck\Application\Shared\Command\CommandInterface;
use Deck\Domain\Table\TableId;

final class CreateTableCommand implements CommandInterface
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
