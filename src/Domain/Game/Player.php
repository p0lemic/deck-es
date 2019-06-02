<?php

namespace Deck\Domain\Game;

class Player
{
    /** @var UuidInterface */
    private $id;
    /** @var string */
    private $username;

    public function __construct(string $username)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->username = $username;
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function username(): string
    {
        return $this->username;
    }
}
