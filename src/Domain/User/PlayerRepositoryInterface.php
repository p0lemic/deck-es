<?php

namespace Deck\Domain\User;

use Deck\Domain\Aggregate\AggregateId;
use Deck\Domain\User\ValueObject\Email;

interface PlayerRepositoryInterface
{
    /**
     * @param PlayerId $playerId
     * @return Player
     */
    public function findById(PlayerId $playerId): ?Player;

    public function findByIdOrFail(PlayerId $playerId): Player;

    public function findByEmailOrFail(Email $email): Player;

    public function existsEmail(Email $email): ?AggregateId;

    public function getCredentialsByEmail(Email $email): array;

    /**
     * @param Player $player
     */
    public function save(Player $player): void;

    /**
     * @return void
     */
    public function clearMemory(): void;
}
