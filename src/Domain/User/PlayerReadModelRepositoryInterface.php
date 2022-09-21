<?php

namespace Deck\Domain\User;

use Deck\Domain\Shared\AggregateId;
use Deck\Domain\User\ValueObject\Email;

interface PlayerReadModelRepositoryInterface
{
    public function findById(PlayerId $playerId): ?PlayerReadModel;
    public function findByIdOrFail(PlayerId $playerId): PlayerReadModel;
    public function findByEmailOrFail(Email $email): PlayerReadModel;
    public function existsEmail(Email $email): ?AggregateId;
    /** @return array<AggregateId, string, string> */
    public function getCredentialsByEmail(Email $email): array;
    public function save(PlayerReadModel $player): void;
    public function clearMemory(): void;
}
