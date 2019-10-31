<?php

namespace Deck\Domain\User;

interface PlayerRepositoryInterface
{
    /**
     * @param PlayerId $playerId
     * @return Player
     */
    public function findById(PlayerId $playerId): ?Player;

    /**
     * @param Player $player
     */
    public function save(Player $player) : void;

    /**
     * @return void
     */
    public function clearMemory(): void;
}
