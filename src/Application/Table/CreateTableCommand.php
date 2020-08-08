<?php

declare(strict_types=1);

namespace Deck\Application\Table;

use Deck\Domain\User\PlayerId;

final class CreateTableCommand
{
    private PlayerId $playerId;

    public function __construct(
        PlayerId $aPlayerId
    ) {
        $this->playerId = $aPlayerId;
    }

    public function playerId(): PlayerId
    {
        return $this->playerId;
    }
}
