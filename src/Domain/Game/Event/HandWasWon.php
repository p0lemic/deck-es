<?php

declare(strict_types=1);

namespace Deck\Domain\Game\Event;

use Deck\Domain\Game\Card;
use Deck\Domain\Game\GameId;
use Deck\Domain\Game\Rank;
use Deck\Domain\Game\Suite;
use Deck\Domain\Shared\AggregateId;
use Deck\Domain\Shared\DomainEvent;
use Deck\Domain\Shared\ValueObject\DateTime;
use Deck\Domain\User\PlayerId;

class HandWasWon implements DomainEvent
{
    private array $cards;
    private PlayerId $playerId;
    private DateTime $occurredOn;

    public function __construct(
        public readonly GameId $aggregateId,
        PlayerId $playerId,
        array $cards,
        DateTime $occurredOn
    ) {
        $this->playerId = $playerId;
        $this->cards = $cards;
        $this->occurredOn = $occurredOn;
    }

    public function playerId(): PlayerId
    {
        return $this->playerId;
    }

    public function cards(): array
    {
        return $this->cards;
    }

    public function occurredOn(): DateTime
    {
        return $this->occurredOn;
    }

    public function normalize(): array
    {
        return [
            'aggregateId' => $this->aggregateId->value(),
            'playerId' => $this->playerId->value(),
            'cards' => array_map(
                static fn (Card $card) => [
                    'suite' => $card->suite->value(),
                    'rank' => $card->rank->value(),
                ],
                $this->cards,
            ),
            'occurredOn' => $this->occurredOn->toString()
        ];
    }

    public static function denormalize(array $payload): self
    {
        return new self(
            GameId::fromString($payload['aggregateId']),
            PlayerId::fromString($payload['playerId']),
            array_map(
                static fn (array $card) => new Card(
                    new Suite($card['suite']),
                    new Rank($card['rank'])
                ),
                $payload['cards'],
            ),
            DateTime::fromString($payload['occurredOn'])
        );
    }
}
