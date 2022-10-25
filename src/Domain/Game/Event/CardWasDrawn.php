<?php

declare(strict_types=1);

namespace Deck\Domain\Game\Event;

use Deck\Domain\Game\Card;
use Deck\Domain\Game\GameId;
use Deck\Domain\Game\Rank;
use Deck\Domain\Game\Suite;
use Deck\Domain\Shared\DomainEvent;
use Deck\Domain\Shared\ValueObject\DateTime;

class CardWasDrawn implements DomainEvent
{
    private Card $card;
    private DateTime $occurredOn;

    public function __construct(
        public readonly GameId $aggregateId,
        Card $card,
        DateTime $occurredOn
    ) {
        $this->card = $card;
        $this->occurredOn = $occurredOn;
    }

    public function card(): Card
    {
        return $this->card;
    }

    public function occurredOn(): DateTime
    {
        return $this->occurredOn;
    }

    public function normalize(): array
    {
        return [
            'aggregateId' => $this->aggregateId->value(),
            'card' => [
                'suite' => $this->card->suite->value(),
                'rank' => $this->card->rank->value(),
            ],
            'occurredOn' => $this->occurredOn->toString()
        ];
    }

    public static function denormalize(array $payload): self
    {
        return new self(
            GameId::fromString($payload['aggregateId']),
            new Card(
                new Suite($payload['card']['suite']),
                new Rank($payload['card']['rank']),
            ),
            DateTime::fromString($payload['occurredOn'])
        );
    }
}
