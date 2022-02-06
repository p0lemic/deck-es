<?php

declare(strict_types=1);

namespace Deck\Domain\Table\Event;

use Deck\Domain\Shared\ValueObject\DateTime;
use Deck\Domain\Table\TableId;

class TableWasCreated
{
    private TableId $aggregateId;
    private DateTime $occurredOn;

    public function __construct(
        TableId $id,
        DateTime $occurredOn
    ) {
        $this->aggregateId = $id;
        $this->occurredOn = $occurredOn;
    }

    public function aggregateId(): TableId
    {
        return $this->aggregateId;
    }

    public function occurredOn(): DateTime
    {
        return $this->occurredOn;
    }

    public function __toString(): string
    {
        return $this->aggregateId()->value();
    }

    public function normalize(): array
    {
        return [
            'aggregateId' => $this->aggregateId()->value(),
            'occurredOn' => $this->occurredOn()->toString()
        ];
    }

    public static function denormalize(array $payload): self
    {
        return new self(
            TableId::fromString($payload['aggregateId']),
            DateTime::fromString($payload['occurredOn'])
        );
    }
}
