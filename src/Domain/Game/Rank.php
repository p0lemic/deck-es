<?php

namespace Deck\Domain\Game;

use InvalidArgumentException;

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
        'K' => 'K'
    ];

    /** @var string */
    private $value;

    public function __construct(string $value)
    {
        $this->setRank($value);
    }

    private function setRank(string $value): void
    {
        if (!in_array($value, self::AVAILABLE_RANKS, true)) {
            throw new InvalidArgumentException('Invalid rank type '.$value);
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }
}
