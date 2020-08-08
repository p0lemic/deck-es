<?php

declare(strict_types=1);

namespace Deck\Infrastructure\User\Auth;

use Deck\Domain\Shared\AggregateId;
use Deck\Domain\User\ValueObject\Auth\HashedPassword;
use Deck\Domain\User\ValueObject\Email;
use Symfony\Component\Security\Core\Encoder\EncoderAwareInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class Auth implements UserInterface, EncoderAwareInterface
{
    private AggregateId $id;
    private Email $email;
    private HashedPassword $hashedPassword;

    private function __construct(AggregateId $id, Email $email, HashedPassword $hashedPassword)
    {
        $this->id = $id;
        $this->email = $email;
        $this->hashedPassword = $hashedPassword;
    }

    public static function create(AggregateId $id, string $email, string $hashedPassword): self
    {
        return new self($id, Email::fromString($email), HashedPassword::fromHash($hashedPassword));
    }

    public function getUsername(): string
    {
        return $this->email->toString();
    }

    public function getPassword(): string
    {
        return $this->hashedPassword->toString();
    }

    public function getRoles(): array
    {
        return [
            'ROLE_USER',
        ];
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getEncoderName(): string
    {
        return 'bcrypt';
    }

    public function id(): AggregateId
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->email->toString();
    }
}
