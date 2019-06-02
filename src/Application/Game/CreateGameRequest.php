<?php

declare(strict_types=1);

namespace Deck\Application\Game;

use Deck\Application\Game\Exception\InvalidPlayerNumber;

final class CreateGameRequest
{
    private $players;

    public function __construct(array $players)
    {
        if (count($players) < 0) {
            throw InvalidPlayerNumber::biggerThanZero();
        }

        $this->players = $players;
    }

    public function players(): array
    {
        return $this->players;
    }
}
