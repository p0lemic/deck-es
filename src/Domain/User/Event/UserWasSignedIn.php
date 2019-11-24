<?php

declare(strict_types=1);

namespace Deck\Domain\User\Event;

use Assert\Assertion;
use Broadway\Serializer\Serializable;
use Deck\Domain\Aggregate\AggregateId;
use Deck\Domain\Shared\Exception\DateTimeException;
use Deck\Domain\Shared\ValueObject\DateTime;
use Deck\Domain\User\ValueObject\Email;

class UserWasSignedIn implements Serializable
{
    /** @var AggregateId */
    private $aggregateId;
    /** @var Email */
    private $email;
    /** @var DateTime */
    private $occurredOn;

    public function __construct(
        AggregateId $id,
        Email $email,
        DateTime $occurredOn
    ) {
        $this->aggregateId = $id;
        $this->email = $email;
        $this->occurredOn = $occurredOn;
    }

    public function aggregateId(): AggregateId
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
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'uuid');
        Assertion::keyExists($data, 'email');

        return new self(
            AggregateId::fromString($data['aggregate_id']),
            Email::fromString($data['email']),
            DateTime::fromString($data['occurred_on'])
        );
    }

    public function serialize(): array
    {
        return [
            'uuid' => $this->aggregateId->value()->toString(),
            'email' => $this->email->toString(),
            'created_at' => $this->occurredOn()->toString(),
        ];
    }
}
