<?php

declare(strict_types=1);

namespace Deck\Domain\Shared;

use Exception;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class AggregateId
{
    private string $value;

    /**
     * @param UuidInterface|null $uuid
     * @throws Exception
     */
    private function __construct(UuidInterface $uuid = null)
    {
        if ($uuid === null) {
            $uuid = Uuid::uuid4();
        }
        $this->value = $uuid->toString();
    }

    /** @throws Exception */
    public static function fromString(string $string): static
    {
        return new static(Uuid::fromString($string));
    }

    public static function create(): static
    {
        return new static();
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString()
    {
        return $this->value;
    }

    public function equals(AggregateId $aggregateId): bool
    {
        return $this->value === $aggregateId->value();
    }
}
