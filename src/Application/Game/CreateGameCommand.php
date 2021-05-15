<?php

declare(strict_types=1);

namespace Deck\Application\Game;

use Deck\Domain\Game\Brisca;
use Deck\Domain\Game\DeckId;
use Deck\Domain\Game\GameId;
use Deck\Domain\Game\Rules;
use Deck\Domain\Table\TableId;

final class CreateGameCommand
{
    private TableId $tableId;
    private GameId $gameId;
    private DeckId $deckId;
    private Rules $rules;

    public function __construct(string $aTableId)
    {
        $this->tableId = TableId::fromString($aTableId);
        $this->gameId = GameId::create();
        $this->deckId = DeckId::create();
        $this->rules = new Brisca();
    }

    public function tableId(): TableId
    {
        return $this->tableId;
    }

    public function gameId(): GameId
    {
        return $this->gameId;
    }

    public function deckId(): DeckId
    {
        return $this->deckId;
    }

    public function rules(): Brisca|Rules
    {
        return $this->rules;
    }
}
