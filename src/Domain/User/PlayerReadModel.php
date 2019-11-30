<?php

declare(strict_types=1);

namespace Deck\Domain\User;

use Deck\Domain\Shared\ValueObject\DateTime;
use Deck\Domain\User\ValueObject\Auth\Credentials;

class PlayerReadModel
{
    /** @var PlayerId */
    private $id;
    /** @var Credentials */
    private $credentials;
    /** @var DateTime */
    private $createdAt;
    /** @var DateTime */
    private $updatedAt;

    public function __construct(
        PlayerId $id,
        Credentials $credentials,
        DateTime $occurredOn
    ) {
        $this->id = $id;
        $this->credentials = $credentials;
        $this->createdAt = $occurredOn;
        $this->updatedAt = $occurredOn;
    }

    public function id(): PlayerId
    {
        return $this->id;
    }

    public function email(): string
    {
        return (string)$this->credentials->email();
    }

    public function hashedPassword(): string
    {
        return (string)$this->credentials->password();
    }

    public function createdAt(): DateTime
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function getId(): string
    {
        return $this->id()->value()->toString();
    }

    public function changeUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
