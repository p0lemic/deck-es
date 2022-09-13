<?php

declare(strict_types=1);

namespace Deck\Domain\User\Event;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Broadway\Serializer\Serializable;
use Deck\Domain\Shared\DomainEvent;
use Deck\Domain\Shared\Exception\DateTimeException;
use Deck\Domain\Shared\ValueObject\DateTime;
use Deck\Domain\User\PlayerId;
use Deck\Domain\User\ValueObject\Email;

class UserWasSignedIn implements DomainEvent
{
    private PlayerId $aggregateId;
    private Email $email;
    private DateTime $occurredOn;

    public function __construct(
        PlayerId $id,
        Email $email,
        DateTime $occurredOn
    ) {
        $this->aggregateId = $id;
        $this->email = $email;
        $this->occurredOn = $occurredOn;
    }

    public function aggregateId(): PlayerId
    {
        return $this->aggregateId;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function occurredOn(): DateTime
    {
        return $this->occurredOn;
    }

    public function normalize(): array
    {
        return [
            'aggregateId' => $this->aggregateId->value(),
            'email' => $this->email->toString(),
            'occurredOn' => $this->occurredOn()->toString(),
        ];
    }

    public static function denormalize(array $payload): self
    {
        Assertion::keyExists($payload, 'aggregateId');
        Assertion::keyExists($payload, 'email');

        return new self(
            PlayerId::fromString($payload['aggregateId']),
            Email::fromString($payload['email']),
            DateTime::fromString($payload['occurredOn'])
        );
    }
}
