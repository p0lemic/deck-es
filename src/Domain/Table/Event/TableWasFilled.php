<?php

declare(strict_types=1);

namespace Deck\Domain\Table\Event;

use Deck\Domain\Shared\ValueObject\DateTime;
use Deck\Domain\Table\TableId;
use Deck\Domain\User\PlayerId;

class TableWasFilled
{
    /** @var TableId */
    private $aggregateId;
    /** @var PlayerId[] */
    private $players;
    /** @var DateTime */
    private $occurredOn;

    public function __construct(
        TableId $id,
        array $players,
        DateTime $occurredOn
    ) {
        $this->aggregateId = $id;
        $this->players = $players;
        $this->occurredOn = $occurredOn;
    }

    public function aggregateId(): TableId
    {
        return $this->aggregateId;
    }

    public function players(): array
    {
        return $this->players;
    }

    public function occurredOn(): DateTime
    {
        return $this->occurredOn;
    }

    public function __toString(): string
    {
        return $this->aggregateId()->value();
    }
}
