<?php

declare(strict_types=1);

namespace Deck\Domain\User;

use Deck\Domain\Shared\AggregateId;

interface PlayerRepositoryInterface
{
    public function get(AggregateId $id): Player;

    public function store(Player $player): void;
}
