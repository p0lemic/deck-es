<?php

declare(strict_types=1);

namespace Deck\Domain\Game\Event;

use Deck\Domain\Game\DeckId;
use Deck\Domain\Game\GameId;
use Deck\Domain\Game\Rules;
use Deck\Domain\Shared\ValueObject\DateTime;
use Deck\Domain\User\PlayerId;
use function get_class;

class GameWasCreated
{
    private GameId $aggregateId;
    /** @var PlayerId[] */
    private array $players;
    private DeckId $deckId;
    private string $rules;
    private DateTime $occurredOn;

    public function __construct(
        GameId $id,
        array $players,
        DeckId $deckId,
        Rules $rules,
        DateTime $occurredOn
    ) {
        $this->aggregateId = $id;
        $this->players = $players;
        $this->deckId = $deckId;
        $this->rules = get_class($rules);
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

    public function deckId(): DeckId
    {
        return $this->deckId;
    }

    public function rules(): string
    {
        return $this->rules;
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
