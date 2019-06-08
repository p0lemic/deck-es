<?php

declare(strict_types=1);

namespace Deck\Domain\Aggregate;

use Exception;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class AggregateId
{
    /** @var UuidInterface */
    private $value;

    /**
     * @param UuidInterface|null $uuid
     * @throws Exception
     */
    private function __construct(UuidInterface $uuid = null)
    {
        if ($uuid === null) {
            $uuid = Uuid::uuid4();
        }
        $this->value = $uuid;
    }

    public static function fromString($string)
    {
        return new static(Uuid::fromString($string));
    }

    public static function create()
    {
        return new static();
    }

    public function value(): UuidInterface
    {
        return $this->value;
    }

    public function __toString()
    {
        return (string)$this->value;
    }

    public function equals(AggregateId $aggregateId): bool
    {
        return $this->value === $aggregateId->value();
    }
}
