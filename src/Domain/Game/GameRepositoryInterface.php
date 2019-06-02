<?php

namespace Deck\Domain\Game;

interface GameRepositoryInterface
{
    /**
     * @param int $gameID
     * @return Game
     */
    public function findByGameId(int $gameID) : Game;

    /**
     * @param int $tableId
     * @return Game
     */
    public function findByTableId(int $tableId) : Game;

    /**
     * @param Game $game
     */
    public function save(Game $game) : void;

    /**
     * @return void
     */
    public function clearMemory(): void;
}
