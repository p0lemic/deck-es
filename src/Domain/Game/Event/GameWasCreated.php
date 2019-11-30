<?php

declare(strict_types=1);

namespace Deck\Domain\Game\Event;

use Deck\Domain\Game\Deck;
use Deck\Domain\Game\GameId;
use Deck\Domain\Game\Player;
use Deck\Domain\Shared\ValueObject\DateTime;

class GameWasCreated
{
    /** @var GameId */
    private $aggregateId;
    /** @var Player[] */
    private $players;
    /** @var Deck */
    private $deck;
    /** @var DateTime */
    private $occurredOn;

    public function __construct(
        GameId $id,
        array $players,
        Deck $deck,
        DateTime $occurredOn
    ) {
        $this->aggregateId = $id;
        $this->players = $players;
        $this->deck = $deck;
        $this->occurredOn = $occurredOn;
    }

    public function players(): array
    {
        return $this->players;
    }

    public function deck(): Deck
    {
        return $this->deck;
    }

    public function aggregateId(): GameId
    {
        return $this->aggregateId;
    }

    public function occurredOn(): DateTime
    {
        return $this->occurredOn;
    }

    public function __toString(): string
    {
        return $this->aggregateId()->value()->toString();
    }
}
