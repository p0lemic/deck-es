<?php

declare(strict_types=1);

namespace Deck\Domain\Game;

use Deck\Domain\User\PlayerId;
use Exception;

interface Rules
{
    /**
     * @param array<string, Card> $cards
     * @return PlayerId
     * @throws Exception
     */
    public function resolveHand(array $cards): PlayerId;

    public function setSampleCard(Card $sampleCard): void;
}
