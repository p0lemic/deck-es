<?php

declare(strict_types=1);

namespace Deck\Domain\Game\Event;

use Deck\Domain\Game\Card;
use Deck\Domain\Game\Rank;
use Deck\Domain\Game\Suite;
use Deck\Domain\Shared\DomainEvent;
use Deck\Domain\Shared\Exception\DateTimeException;
use Deck\Domain\Shared\ValueObject\DateTime;

class CardWasAddedToDeck implements DomainEvent
{
    private Card $card;
    private DateTime $occurredOn;

    public function __construct(
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
            'card' => [
                'suite' => $this->card->suite->value(),
                'rank' => $this->card->rank->value(),
            ],
            'occurredOn' => $this->occurredOn->toString()
        ];
    }

    /**
     * @param array<string, string> $payload
     * @return CardWasAddedToDeck
     * @throws DateTimeException
     */
    public static function denormalize(array $payload): self
    {
        return new self(
            new Card(
                new Suite($payload['card']['suite']),
                new Rank($payload['card']['rank']),
            ),
            DateTime::fromString($payload['occurredOn'])
        );
    }
}
