<?php

declare(strict_types=1);

namespace Deck\Domain\Game;

interface Rules
{
    public function resolveHand(Card $firstCard, Card $secondCard): int;
}
