<?php
declare(strict_types=1);

namespace Deck\Infrastructure\Serializer;

use Assert\Assertion as Assert;
use Broadway\Serializer\Serializer;
use Deck\Domain\Shared\DomainEvent;
use InvalidArgumentException;
use function get_class;

class JsonSerializer implements Serializer
{
    public function serialize($object): array
    {
        return [
            'class' => get_class($object),
            'payload' => $object->normalize(),
        ];
    }

    public function deserialize(array $serializedObject): DomainEvent
    {
        Assert::keyExists($serializedObject, 'class', "Key 'class' should be set.");
        Assert::keyExists($serializedObject, 'payload', "Key 'payload' should be set.");

        /** @var DomainEvent $class */
        $class = $serializedObject['class'];

        $payload = $serializedObject['payload'];

        if (! is_array($payload)) {
            throw new InvalidArgumentException("DomainEvent is not valid because the payload not exists");
        }

        return $class::denormalize($payload);
    }
}
