<?php

namespace Deck\Domain\Game;

interface GameReadModelRepositoryInterface
{
    public function findByGameId(GameId $gameId): ?GameReadModel;
    public function all(): array;
    public function save(GameReadModel $game): void;
    public function clearMemory(): void;
}
