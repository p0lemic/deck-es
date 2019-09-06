<?php

namespace Deck\Domain\Event;

use DateTimeInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Event
{
    public const STREAM_NAME_DEFAULT = 'master';
    public const STREAM_VERSION_DEFAULT = 1;

    /** @var UuidInterface */
    private $eventId;
    /** @var string */
    private $eventBody;
    /** @var string */
    private $eventType;
    /** @var string */
    private $streamName;
    /** @var int */
    private $streamVersion;
    /** @var DateTimeInterface */
    private $occurredOn;

    /**
     * @param string $aTypeName
     * @param string $anEventBody
     * @param DateTimeInterface $anOccurredOn
     * @throws \Exception
     */
    public function __construct(string $aTypeName, string $anEventBody, DateTimeInterface $anOccurredOn)
    {
        $this->eventId = Uuid::uuid4();
        $this->eventBody = $anEventBody;
        $this->eventType = $aTypeName;
        $this->occurredOn = $anOccurredOn;
        $this->streamName = self::STREAM_NAME_DEFAULT;
        $this->streamVersion = self::STREAM_VERSION_DEFAULT;
    }

    public function eventBody(): string
    {
        return $this->eventBody;
    }

    public function eventType(): string
    {
        return $this->eventType;
    }

    public function eventId(): UuidInterface
    {
        return $this->eventId;
    }

    public function occurredOn(): DateTimeInterface
    {
        return $this->occurredOn;
    }

    public function streamName(): string
    {
        return $this->streamName;
    }

    public function streamVersion(): int
    {
        return $this->streamVersion;
    }
}
