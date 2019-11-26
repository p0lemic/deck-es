<?php

declare(strict_types=1);

namespace Deck\Domain\User\Event;

use Assert\Assertion;
use Broadway\Serializer\Serializable;
use Deck\Domain\Aggregate\AggregateId;
use Deck\Domain\Shared\Exception\DateTimeException;
use Deck\Domain\Shared\ValueObject\DateTime;
use Deck\Domain\User\PlayerId;
use Deck\Domain\User\ValueObject\Auth\Credentials;
use Deck\Domain\User\ValueObject\Auth\HashedPassword;
use Deck\Domain\User\ValueObject\Email;

class UserWasCreated implements Serializable
{
    /** @var PlayerId */
    private $aggregateId;
    /** @var Credentials */
    private $credentials;
    /** @var DateTime */
    private $occurredOn;

    public function __construct(
        PlayerId $id,
        Credentials $credentials,
        DateTime $occurredOn
    ) {
        $this->aggregateId = $id;
        $this->credentials = $credentials;
        $this->occurredOn = $occurredOn;
    }

    public function aggregateId(): PlayerId
    {
        return $this->aggregateId;
    }

    public function credentials(): Credentials
    {
        return $this->credentials;
    }

    public function occurredOn(): DateTime
    {
        return $this->occurredOn;
    }

    /**
     * @param array $data
     * @return UserWasCreated
     * @throws DateTimeException
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'aggregate_id');
        Assertion::keyExists($data, 'credentials');

        return new self(
            PlayerId::fromString($data['aggregate_id']),
            new Credentials(
                Email::fromString($data['credentials']['email']),
                HashedPassword::fromHash($data['credentials']['password'])
            ),
            DateTime::fromString($data['occurred_on'])
        );
    }

    public function serialize(): array
    {
        return [
            'aggregate_id' => $this->aggregateId->value()->toString(),
            'credentials' => [
                'email' => $this->credentials->email()->toString(),
                'password' => $this->credentials->password()->toString(),
            ],
            'occurred_on' => $this->occurredOn()->toString(),
        ];
    }
}
