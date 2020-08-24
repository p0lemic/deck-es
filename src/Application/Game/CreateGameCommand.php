<?php

declare(strict_types=1);

namespace Deck\Application\Game;

use Deck\Domain\Game\GameId;
use Deck\Domain\Table\TableId;
use Deck\Domain\User\Player;

final class CreateGameCommand
{
    private TableId $tableId;
    private GameId $gameId;

    public function __construct(string $aTableId)
    {
        $this->tableId = TableId::fromString($aTableId);
        $this->gameId = GameId::create();
    }

    public function tableId(): TableId
    {
        return $this->tableId;
    }

    public function gameId(): GameId
    {
        return $this->gameId;
    }
}
