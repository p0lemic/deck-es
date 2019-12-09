<?php

declare(strict_types=1);

namespace Deck\Domain\Game\Event;

use Deck\Domain\Game\Deck;
use Deck\Domain\Shared\ValueObject\DateTime;

class GameWasInit
{
    /** @var DateTime */
    private $occurredOn;
    /** @var Deck */
    private $deck;

    public function __construct(
        Deck $deck,
        DateTime $occurredOn
    ) {
        $this->occurredOn = $occurredOn;
        $this->deck = $deck;
    }

    public function deck(): Deck
    {
        return $this->deck;
    }

    public function occurredOn(): DateTime
    {
        return $this->occurredOn;
    }
}
