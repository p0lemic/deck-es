<?php

declare(strict_types=1);

namespace Deck\Application\Table;

use Deck\Domain\Table\TableId;
use Deck\Domain\User\PlayerId;

final class PlayerSitOnTableCommand
{
    /** @var TableId */
    private $tableId;
    /** @var PlayerId */
    private $playerId;

    public function __construct(
        string $aTableId,
        string $aPlayerId
    ) {
        $this->tableId = TableId::fromString($aTableId);
        $this->playerId = PlayerId::fromString($aPlayerId);
    }

    public function tableId(): TableId
    {
        return $this->tableId;
    }

    public function playerId(): PlayerId
    {
        return $this->playerId;
    }
}
