<?php

namespace Deck\Domain\Deck;

use Deck\Domain\Deck\Exception\InvalidSuiteException;

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

    /**
     * @param string $value
     * @return void
     * @throws InvalidSuiteException
     */
    private function setSuite(string $value): void
    {
        if (!in_array($value, self::AVAILABLE_SUITES, true)) {
            throw InvalidSuiteException::fromSuiteString($value);
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
