<?php

declare(strict_types=1);

namespace Deck\Domain\User\Event;

use Deck\Domain\Shared\ValueObject\DateTime;
use Deck\Domain\User\PlayerId;
use Deck\Domain\User\ValueObject\Auth\Credentials;

class UserWasCreated
{
    private PlayerId $aggregateId;
    private Credentials $credentials;
    private DateTime $occurredOn;

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
}
