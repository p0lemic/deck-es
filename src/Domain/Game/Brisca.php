<?php

declare(strict_types=1);

namespace Deck\Domain\Game;

class Brisca implements Rules
{
    public function resolveHand(
        Card $firstCard,
        Card $secondCard
    ): int {
        return -1;
    }
}
