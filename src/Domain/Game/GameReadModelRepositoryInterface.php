<?php

namespace Deck\Domain\Game;

interface GameReadModelRepositoryInterface
{
    /**
     * @param GameId $gameId
     * @return GameReadModel
     */
    public function findByGameId(GameId $gameId): ?GameReadModel;

    public function all(): array;

    /**
     * @param GameReadModel $game
     */
    public function save(GameReadModel $game): void;

    /**
     * @return void
     */
    public function clearMemory(): void;
}
