<?php

namespace Deck\Domain\Table;

use Deck\Domain\User\PlayerId;
use function count;

class TableReadModel
{
    private const SIZE = 2;

    private string $id;
    private array $players;

    public function __construct(
        string $tableId,
        array $players,
    ) {
        $this->id = $tableId;
        $this->players = $players;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function players(): array
    {
        return $this->players;
    }

    public function joinPlayer(PlayerId $playerId): void
    {
        $this->players[] = $playerId->value();
    }

    public function removePlayer(PlayerId $playerId): void
    {
        foreach ($this->players as $key => $player) {
            if ($player === $playerId->value()) {
                unset($this->players[$key]);
            }
        }
    }

    public function isFull(): bool
    {
        return count($this->players) === self::SIZE;
    }

    public function normalize(): array
    {
        return [
            'id' => $this->id,
            'players' => $this->players
        ];
    }

    public static function denormalize(
        string $id,
        array $players
    ): self {
        return new self(
            $id,
            $players
        );
    }
}
