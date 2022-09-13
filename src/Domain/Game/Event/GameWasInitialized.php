<?php

declare(strict_types=1);

namespace Deck\Domain\Game\Event;

use Deck\Domain\Shared\DomainEvent;
use Deck\Domain\Shared\ValueObject\DateTime;

class GameWasInitialized implements DomainEvent
{
    private DateTime $occurredOn;

    public function __construct(
        DateTime $occurredOn
    ) {
        $this->occurredOn = $occurredOn;
    }

    public function occurredOn(): DateTime
    {
        return $this->occurredOn;
    }

    public function normalize(): array
    {
        return [
            'occurredOn' => $this->occurredOn->toString()
        ];
    }

    public static function denormalize(array $payload): self
    {
        return new self(
            DateTime::fromString($payload['occurredOn'])
        );
    }
}
