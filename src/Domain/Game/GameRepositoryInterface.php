<?php

namespace Deck\Domain\Game;

interface GameRepositoryInterface
{
    /**
     * @param int $gameID
     * @return Game
     */
    public function findByGameId(string $gameID): ?Game;

    /**
     * @param int $tableId
     * @return Game
     */
    public function findByTableId(string $tableId): ?Game;

    /**
     * @param Game $game
     */
    public function save(Game $game) : void;

    /**
     * @return void
     */
    public function clearMemory(): void;
}
