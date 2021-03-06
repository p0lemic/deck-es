<?php

namespace Deck\Domain\Game;

use Deck\Domain\Game\Exception\InvalidRankException;

class Rank
{
    public const AVAILABLE_RANKS = [
        'A' => 'A',
        '2' => '2',
        '3' => '3',
        '4' => '4',
        '5' => '5',
        '6' => '6',
        '7' => '7',
        'J' => 'J',
        'Q' => 'Q',
        'K' => 'K',
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
