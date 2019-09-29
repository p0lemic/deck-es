<?php

namespace Deck\Domain\User;

interface PlayerRepositoryInterface
{
    /**
     * @param string $playerId
     * @return Player
     */
    public function findById(string $playerId): ?Player;

    /**
     * @param Player $player
     */
    public function save(Player $player) : void;

    /**
     * @return void
     */
    public function clearMemory(): void;
}
