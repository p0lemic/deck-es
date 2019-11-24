<?php

namespace Deck\Domain\User;

use Deck\Domain\Aggregate\AggregateId;
use Deck\Domain\User\ValueObject\Email;

interface PlayerReadModelRepositoryInterface
{
    /**
     * @param PlayerId $playerId
     * @return PlayerReadModel
     */
    public function findById(PlayerId $playerId): ?PlayerReadModel;

    public function findByIdOrFail(PlayerId $playerId): PlayerReadModel;

    public function findByEmailOrFail(Email $email): PlayerReadModel;

    public function existsEmail(Email $email): ?AggregateId;

    public function getCredentialsByEmail(Email $email): array;

    /** @param PlayerReadModel $player */
    public function save(PlayerReadModel $player): void;

    /** @return void */
    public function clearMemory(): void;
}
