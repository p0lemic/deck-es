<?php

declare(strict_types=1);

namespace Deck\Domain\User;

use Deck\Domain\Aggregate\AggregateId;

interface PlayerRepositoryInterface
{
    public function get(AggregateId $id): Player;

    public function store(Player $user): void;
}
