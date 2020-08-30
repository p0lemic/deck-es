<?php

declare(strict_types=1);

namespace Deck\Domain\Game\Event;

use Deck\Domain\Shared\ValueObject\DateTime;

class GameWasInitialized
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
}
