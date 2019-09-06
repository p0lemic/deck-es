<?php

namespace Deck\Infrastructure\Serializer;

use Deck\Domain\Event\DomainEvent;
use Deck\Domain\Event\Serializer;
use JMS\Serializer\SerializerInterface;

class JsonSerializer implements Serializer
{
    /** @var SerializerInterface */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function serialize(DomainEvent $data): string
    {
        return $this->serializer->serialize($data, 'json');
    }

    public function deserialize(string $data, string $type): DomainEvent
    {
        return $this->serializer->deserialize($data, $type, 'json');
    }
}
