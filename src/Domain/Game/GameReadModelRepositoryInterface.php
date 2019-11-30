<?php

namespace Deck\Domain\Game;

interface GameReadModelRepositoryInterface
{
    /**
     * @param string $gameID
     * @return GameReadModel
     */
    public function findByGameId(string $gameID): ?GameReadModel;

    /**
     * @param string $tableId
     * @return GameReadModel
     */
    public function findByTableId(string $tableId): ?GameReadModel;

    /**
     * @param GameReadModel $game
     */
    public function save(GameReadModel $game): void;

    /**
     * @return void
     */
    public function clearMemory(): void;
}
