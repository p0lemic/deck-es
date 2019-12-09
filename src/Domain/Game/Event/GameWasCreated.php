<?php

declare(strict_types=1);

namespace Deck\Domain\Game\Event;

use Deck\Domain\Game\GameId;
use Deck\Domain\Game\Player;
use Deck\Domain\Shared\ValueObject\DateTime;

class GameWasCreated
{
    /** @var GameId */
    private $aggregateId;
    /** @var Player[] */
    private $players;
    /** @var DateTime */
    private $occurredOn;

    public function __construct(
        GameId $id,
        array $players,
        DateTime $occurredOn
    ) {
        $this->aggregateId = $id;
        $this->players = $players;
        $this->occurredOn = $occurredOn;
    }

    public function players(): array
    {
        return $this->players;
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
