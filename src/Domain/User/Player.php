<?php

namespace Deck\Domain\User;

use Deck\Domain\Aggregate\Aggregate;
use Deck\Domain\Deck\Card;
use Deck\Domain\User\Events\UserWasCreated;
use Deck\Domain\User\ValueObject\Auth\Credentials;

class Player extends Aggregate
{
    /** @var array */
    private $hand;

    private function __construct()
    {
        $this->hand = [];
    }

    public static function create(Credentials $credentials): self {
        $user = new self();

        $user->apply(new UserWasCreated(PlayerId::create(), $credentials));

        return $user;
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
