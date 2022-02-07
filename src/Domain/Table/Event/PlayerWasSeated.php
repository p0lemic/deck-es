<?php

declare(strict_types=1);

namespace Deck\Domain\Table\Event;

use Deck\Domain\Shared\ValueObject\DateTime;
use Deck\Domain\Table\TableId;
use Deck\Domain\User\PlayerId;

class PlayerWasSeated
{
    private TableId $aggregateId;
    private PlayerId $playerId;
    private DateTime $occurredOn;

    public function __construct(
        TableId $id,
        PlayerId $playerId,
        DateTime $occurredOn
    ) {
        $this->aggregateId = $id;
        $this->playerId = $playerId;
        $this->occurredOn = $occurredOn;
    }

    public function aggregateId(): TableId
    {
        return $this->aggregateId;
    }

    public function playerId(): PlayerId
    {
        return $this->playerId;
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
            'aggregateId' => $this->aggregateId->value(),
            'playerId' => $this->playerId->value(),
            'occurredOn' => $this->occurredOn->toString()
        ];
    }

    public static function denormalize(array $payload): self
    {
        return new self(
            TableId::fromString($payload['aggregateId']),
            PlayerId::fromString($payload['playerId']),
            DateTime::fromString($payload['occurredOn'])
        );
    }
}
