<?php

declare(strict_types=1);

namespace Deck\Infrastructure\User;

use DateTime;
use Deck\Domain\User\ValueObject\Auth\Credentials;
use Deck\Domain\User\ValueObject\Email;
use Ramsey\Uuid\UuidInterface;

class PlayerProjection
{
    /** @var UuidInterface */
    private $uuid;

    /** @var Credentials */
    private $credentials;

    /** @var DateTime */
    private $createdAt;

    /** @var DateTime */
    private $updatedAt;

    public function uuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function email(): string
    {
        return (string) $this->credentials->email();
    }

    public function changeUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function hashedPassword(): string
    {
        return (string) $this->credentials->password();
    }

    public function getId(): string
    {
        return $this->uuid->toString();
    }
}
