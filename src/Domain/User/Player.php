<?php

namespace Deck\Domain\User;

use App\Domain\User\Event\UserSignedIn;
use DateTime;
use DateTimeInterface;
use Deck\Domain\Aggregate\Aggregate;
use Deck\Domain\Deck\Card;
use Deck\Domain\User\Event\UserWasCreated;
use Deck\Domain\User\Exception\InvalidCredentialsException;
use Deck\Domain\User\Specification\UniqueEmailSpecificationInterface;
use Deck\Domain\User\ValueObject\Auth\Credentials;
use Deck\Domain\User\ValueObject\Auth\HashedPassword;
use Deck\Domain\User\ValueObject\Email;

class Player extends Aggregate
{
    /** @var Email */
    private $email;
    /** @var HashedPassword */
    private $hashedPassword;
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

    /**
     * @param string $plainPassword
     * @throws InvalidCredentialsException
     */
    public function signIn(string $plainPassword): void
    {
        $match = $this->hashedPassword->match($plainPassword);

        if (!$match) {
            throw InvalidCredentialsException::invalid();
        }

        $this->apply(new UserSignedIn($this->id, $this->email));
    }

    protected function applyUserWasCreated(UserWasCreated $event): void
    {
        $this->id = $event->aggregateId();
        $this->setEmail($event->credentials()->email());
        $this->setHashedPassword($event->credentials()->password());
        $this->setCreatedAt($event->occurredOn());
    }

    private function setEmail(Email $email): void
    {
        $this->email = $email;
    }

    private function setHashedPassword(HashedPassword $hashedPassword): void
    {
        $this->hashedPassword = $hashedPassword;
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

    public function email(): string
    {
        return $this->email->toString();
    }
}
