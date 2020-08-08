<?php

declare(strict_types=1);

namespace Deck\Domain\User\Event;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Broadway\Serializer\Serializable;
use Deck\Domain\Aggregate\AggregateId;
use Deck\Domain\Shared\Exception\DateTimeException;
use Deck\Domain\Shared\ValueObject\DateTime;
use Deck\Domain\User\PlayerId;
use Deck\Domain\User\ValueObject\Email;

class UserWasSignedIn implements Serializable
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

    /**
     * @param array $data
     * @return UserWasSignedIn
     * @throws DateTimeException
     * @throws AssertionFailedException
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'aggregate_id');
        Assertion::keyExists($data, 'email');

        return new self(
            PlayerId::fromString($data['aggregate_id']),
            Email::fromString($data['email']),
            DateTime::fromString($data['occurred_on'])
        );
    }

    public function serialize(): array
    {
        return [
            'aggregate_id' => $this->aggregateId->value(),
            'email' => $this->email->toString(),
            'occurred_on' => $this->occurredOn()->toString(),
        ];
    }
}
