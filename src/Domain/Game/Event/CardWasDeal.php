<?php

declare(strict_types=1);

namespace Deck\Domain\Game\Event;

use DateTimeImmutable;
use DateTimeInterface;
use Deck\Domain\Game\Card;
use Deck\Domain\Shared\AggregateId;
use Deck\Domain\User\PlayerId;

class CardWasDeal
{
    /** @var Card */
    private $card;
    /** @var DateTimeInterface */
    private $occurredOn;
    /** @var PlayerId */
    private $playerId;

    public function __construct(
        AggregateId $playerId,
        Card $card
    ) {
        $this->playerId = $playerId;
        $this->card = $card;
        $this->occurredOn = new DateTimeImmutable();
    }

    public function playerId(): AggregateId
    {
        return $this->playerId;
    }

    public function card(): Card
    {
        return $this->card;
    }

    public function occurredOn(): DateTimeInterface
    {
        return $this->occurredOn;
    }
}
