<?php

namespace Deck\Domain\Game;

interface GameReadModelRepositoryInterface
{
    public function findByGameId(GameId $gameId): GameReadModel;
    /** @return GameReadModel[] */
    public function all(): array;
    public function save(GameReadModel $game): void;
    public function update(GameReadModel $gameReadModel): void;
}
