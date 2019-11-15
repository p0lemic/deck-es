<?php

namespace Deck\Domain\User;

use DateTime;
use DateTimeInterface;
use Deck\Domain\Aggregate\Aggregate;
use Deck\Domain\Deck\Card;
use Deck\Domain\User\Event\UserSignedIn;
use Deck\Domain\User\Event\UserWasCreated;
use Deck\Domain\User\Exception\InvalidCredentialsException;
use Deck\Domain\User\Specification\UniqueEmailSpecificationInterface;
use Deck\Domain\User\ValueObject\Auth\Credentials;

class Player extends Aggregate
{
    /** @var Credentials */
    private $credentials;
    /** @var DateTime */
    private $createdAt;
    /** @var DateTime|null */
    private $updatedAt;
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

        $user->recordThatAndApply(new UserWasCreated(PlayerId::create(), $credentials));

        return $user;
    }

    public function credentials(): Credentials
    {
        return $this->credentials;
    }

    public function email(): string
    {
        return (string)$this->credentials->email();
    }

    public function hashedPassword(): string
    {
        return (string)$this->credentials->password();
    }

    public function hand(): array
    {
        return $this->hand;
    }

    public function addCardToPlayersHand(Card $card): void
    {
        $this->hand[] = $card;
    }

    /**
     * @param string $plainPassword
     * @throws InvalidCredentialsException
     */
    public function signIn(string $plainPassword): void
    {
        $match = $this->credentials->password()->match($plainPassword);

        if (!$match) {
            throw InvalidCredentialsException::invalid();
        }

        $this->recordThatAndApply(new UserSignedIn($this->id, $this->credentials->email()));
    }

    protected function applyUserWasCreated(UserWasCreated $event): void
    {
        $this->setAggregateId($event->aggregateId());
        $this->setCredentials($event->credentials());
        $this->setCreatedAt($event->occurredOn());
        $this->setUpdatedAt($event->occurredOn());
    }

    protected function applyUserSignedIn(UserSignedIn $event): void
    {
        $this->setUpdatedAt($event->occurredOn());
    }

    private function setCredentials(Credentials $credentials): void
    {
        $this->credentials = $credentials;
    }

    private function setCreatedAt(DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    private function setUpdatedAt(DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function createdAt(): DateTime
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }
}
