<?php

declare(strict_types=1);

namespace Deck\Domain\Game;

use Deck\Domain\User\PlayerId;
use Exception;
use function array_keys;

class Brisca implements Rules
{
    public const MAX_CARDS_IN_PLAYER_HAND = 3;
    private ?Card $sampleCard = null;

    /**
     * @param array<string, Card> $cards
     * @return PlayerId
     * @throws Exception
     */
    public function resolveHand(array $cards): PlayerId
    {
        [$firstPlayerId, $secondPlayerId] = array_keys($cards);

        $firstPlayerCard = $cards[$firstPlayerId];
        $secondPlayerCard = $cards[$secondPlayerId];

        if ($firstPlayerCard->suite()->equals($secondPlayerCard->suite())) {
            return $this->getPoints($firstPlayerCard->rank()) > $this->getPoints($secondPlayerCard->rank()) ?
                PlayerId::fromString($firstPlayerId) :
                PlayerId::fromString(
                    $secondPlayerId
                );
        }

        if ($firstPlayerCard->suite()->equals($this->sampleCard?->suite())) {
            return PlayerId::fromString($firstPlayerId);
        }

        if ($secondPlayerCard->suite()->equals($this->sampleCard?->suite())) {
            return PlayerId::fromString($secondPlayerId);
        }

        return PlayerId::fromString($firstPlayerId);
    }

    public function setSampleCard(Card $sampleCard): void
    {
        $this->sampleCard = $sampleCard;
    }

    private function getPoints(Rank $rank): int
    {
        return match ($rank->value()) {
            'A' => 11,
            '3' => 10,
            'J' => 2,
            'Q' => 3,
            'K' => 4,
            default => 0,
        };
    }
}
