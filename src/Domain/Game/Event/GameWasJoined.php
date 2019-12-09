<?php

declare(strict_types=1);

namespace Deck\Domain\Game\Event;

use DateTimeInterface;
use Deck\Domain\Game\GameId;
use Deck\Domain\Game\Player;
use Deck\Domain\Shared\ValueObject\DateTime;

class GameWasJoined
{
    /** @var DateTime */
    private $occurredOn;
    /** @var Player */
    private $player;
    /** @var GameId */
    private $gameId;

    public function __construct(
        GameId $gameId,
        Player $player,
        DateTime $occurredOn
    ) {
        $this->gameId = $gameId;
        $this->player = $player;
        $this->occurredOn = $occurredOn;
    }

    public function aggregateId(): GameId
    {
        return $this->gameId;
    }

    public function player(): Player
    {
        return $this->player;
    }

    public function occurredOn(): DateTime
    {
        return $this->occurredOn;
    }
}
