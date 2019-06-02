<?php

namespace Deck\Domain\Game;

use InvalidArgumentException;

class Suite
{
    public const AVAILABLE_SUITES = [
        'clubs',
        'spades',
        'hearts',
        'diams'
    ];

    /** @var string */
    private $value;

    public function __construct(string $value)
    {
        $this->setSuite($value);
    }

    private function setSuite(string $value): void
    {
        if (!in_array($value, self::AVAILABLE_SUITES, true)) {
            throw new InvalidArgumentException('Invalid suite type '.$value);
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }
}
