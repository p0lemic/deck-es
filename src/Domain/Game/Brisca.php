<?php

declare(strict_types=1);

namespace Deck\Domain\Game;

class Brisca implements Rules
{
    public const MAX_CARDS_IN_PLAYER_HAND = 3;

    public function resolveHand(
        Card $firstCard,
        Card $secondCard
    ): int {
        return -1;
    }
}
