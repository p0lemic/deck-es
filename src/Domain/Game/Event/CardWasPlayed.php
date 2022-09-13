<?php

declare(strict_types=1);

namespace Deck\Domain\Game\Event;

use Deck\Domain\Game\Card;
use Deck\Domain\Game\Rank;
use Deck\Domain\Game\Suite;
use Deck\Domain\Shared\AggregateId;
use Deck\Domain\Shared\DomainEvent;
use Deck\Domain\Shared\ValueObject\DateTime;
use Deck\Domain\User\PlayerId;

class CardWasPlayed implements DomainEvent
{
    private Card $card;
    private DateTime $occurredOn;
    private PlayerId $playerId;

    public function __construct(
        PlayerId $playerId,
        Card $card,
        DateTime $occurredOn
    ) {
        $this->playerId = $playerId;
        $this->card = $card;
        $this->occurredOn = $occurredOn;
    }

    public function playerId(): AggregateId
    {
        return $this->playerId;
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
            'playerId' => $this->playerId->value(),
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
            PlayerId::fromString($payload['playerId']),
            new Card(
                new Suite($payload['card']['suite']),
                new Rank($payload['card']['rank']),
            ),
            DateTime::fromString($payload['occurredOn'])
        );
    }
}
