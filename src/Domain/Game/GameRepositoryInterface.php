<?php

declare(strict_types=1);

namespace Deck\Domain\Game;

use Deck\Domain\Shared\AggregateId;

interface GameRepositoryInterface
{
    public function get(AggregateId $id): Game;

    public function store(Game $game): void;
}
