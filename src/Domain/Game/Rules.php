<?php

declare(strict_types=1);

namespace Deck\Domain\Game;

use Deck\Domain\User\PlayerId;

interface Rules
{
    public function resolveHand(array $cards): PlayerId;

    public function setSampleCard(Card $sampleCard): void;
}
