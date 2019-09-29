<?php

namespace Deck\Domain\User;

use Deck\Domain\Aggregate\Aggregate;
use Deck\Domain\Deck\Card;
use Deck\Domain\User\Event\UserWasCreated;
use Deck\Domain\User\Specification\UniqueEmailSpecificationInterface;
use Deck\Domain\User\ValueObject\Auth\Credentials;

class Player extends Aggregate
{
    /** @var array */
    private $hand;

    private function __construct()
    {
        $this->hand = [];
    }

    /**
     * @param Credentials $credentials
     * @param UniqueEmailSpecificationInterface $uniqueEmailSpecification
     *
     * @return static
     *
     * @throws Exception\EmailAlreadyExistException
     */
    public static function create(
        Credentials $credentials,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification
    ): self {
        $uniqueEmailSpecification->isUnique($credentials->email());
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
