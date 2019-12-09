<?php

namespace Deck\Infrastructure\Serializer;

use Assert\Assertion as Assert;
use Broadway\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;
use function get_class;

class JsonSerializer implements Serializer
{
    /** @var SerializerInterface */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize($object): array
    {
        return [
            'class' => get_class($object),
            'payload' => $this->serializer->serialize($object, 'json'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function deserialize(array $serializedObject)
    {
        Assert::keyExists($serializedObject, 'class', "Key 'class' should be set.");
        Assert::keyExists($serializedObject, 'payload', "Key 'payload' should be set.");

        return $this->serializer->deserialize($serializedObject['payload'], $serializedObject['class'], 'json');
    }
}
