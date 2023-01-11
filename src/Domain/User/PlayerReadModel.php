<?php

declare(strict_types=1);

namespace Deck\Domain\User;

use Deck\Domain\Shared\ValueObject\DateTime;
use Deck\Domain\User\ValueObject\Auth\Credentials;
use Deck\Domain\User\ValueObject\Auth\HashedPassword;
use Deck\Domain\User\ValueObject\Email;

class PlayerReadModel
{
    private PlayerId $id;
    private Credentials $credentials;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    public function __construct(
        PlayerId $id,
        Credentials $credentials,
        DateTime $occurredOn,
        DateTime $updatedAt,
    ) {
        $this->id = $id;
        $this->credentials = $credentials;
        $this->createdAt = $occurredOn;
        $this->updatedAt = $updatedAt;
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
        return $this->id()->value();
    }

    public function changeUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function normalize(): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->createdAt->toString(),
            'updated_at' => $this->updatedAt->toString(),
            'credentials_email' => $this->email(),
            'credentiasls_password' => $this->hashedPassword()
        ];
    }

    public static function denormalize(
        string $id,
        string $email,
        string $hashedPassword,
        string $createdAt,
        string $updatedAt,
    ): self {
        return new self(
            PlayerId::fromString($id),
            new Credentials(Email::fromString($email), HashedPassword::fromHash($hashedPassword)),
            DateTime::fromString($createdAt),
            DateTime::fromString($updatedAt)
        );
    }
}
