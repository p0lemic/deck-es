<?php

namespace Deck\Domain\Game;

use Deck\Domain\Game\Exception\InvalidRankException;

class Rank
{
    public const AVAILABLE_RANKS = [
        'A',
        '2',
        '3',
        '4',
        '5',
        '6',
        '7',
        'J',
        'Q',
        'K',
    ];

    private string $value;

    public function __construct(string $value)
    {
        $this->setRank($value);
    }

    /**
     * @param string $value
     * @return void
     * @throws InvalidRankException
     */
    private function setRank(string $value): void
    {
        if (!in_array($value, self::AVAILABLE_RANKS, true)) {
            throw InvalidRankException::fromRankString($value);
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
