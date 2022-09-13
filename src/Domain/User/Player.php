<?php

namespace Deck\Domain\User;

use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Deck\Domain\Shared\Exception\DateTimeException;
use Deck\Domain\Shared\ValueObject\DateTime;
use Deck\Domain\User\Event\UserWasCreated;
use Deck\Domain\User\Event\UserWasSignedIn;
use Deck\Domain\User\Exception\InvalidCredentialsException;
use Deck\Domain\User\Specification\UniqueEmailSpecificationInterface;
use Deck\Domain\User\ValueObject\Auth\Credentials;

class Player extends EventSourcedAggregateRoot
{
    private PlayerId $id;
    private Credentials $credentials;
    private DateTime $createdAt;
    private ?DateTime $updatedAt;

    private function __construct()
    {
    }

    /**
     * @throws Exception\EmailAlreadyExistException
     * @throws DateTimeException
     */
    public static function create(
        PlayerId $id,
        Credentials $credentials,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification
    ): self {
        $uniqueEmailSpecification->isUnique($credentials->email());
        $user = new self();

        $user->apply(new UserWasCreated($id, $credentials, DateTime::now()));

        return $user;
    }

    public function getAggregateRootId(): string
    {
        return $this->id->value();
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

    /**
     * @param string $plainPassword
     * @throws InvalidCredentialsException
     * @throws DateTimeException
     */
    public function signIn(string $plainPassword): void
    {
        $match = $this->credentials->password()->match($plainPassword);

        if (!$match) {
            throw InvalidCredentialsException::invalid();
        }

        $this->apply(new UserWasSignedIn($this->id, $this->credentials->email(), DateTime::now()));
    }

    protected function applyUserWasCreated(UserWasCreated $event): void
    {
        $this->id = $event->aggregateId();
        $this->setCredentials($event->credentials());
        $this->setCreatedAt($event->occurredOn());
        $this->setUpdatedAt($event->occurredOn());
    }

    protected function applyUserWasSignedIn(UserWasSignedIn $event): void
    {
        $this->setUpdatedAt($event->occurredOn());
    }

    private function setCredentials(Credentials $credentials): void
    {
        $this->credentials = $credentials;
    }

    private function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    private function setUpdatedAt(DateTime $updatedAt): void
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
