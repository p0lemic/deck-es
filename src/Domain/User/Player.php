<?php

namespace Deck\Domain\User;

use Deck\Domain\Deck\Card;
use Ramsey\Uuid\UuidInterface;

class Player
{
    /** @var UuidInterface */
    private $id;
    /** @var string */
    private $username;
    /** @var array */
    private $hand;

    public function __construct(PlayerId $playerId, string $username)
    {
        $this->id = $playerId;
        $this->username = $username;
        $this->hand = [];
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function hand(): array
    {
        return $this->hand;
    }

    public function addCardToPlayersHand(Card $card): void
    {
        $this->hand[] = $card;
    }
}
