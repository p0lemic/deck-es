<?php

namespace Deck\Infrastructure\Serializer;

use Assert\Assertion as Assert;
use Broadway\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Exception\JsonException;
use function get_class;
use function json_decode;

class JsonSerializer implements Serializer
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize($object): array
    {
        try {
            return [
                'class' => get_class($object),
                'payload' => json_decode($this->serializer->serialize($object, 'json'), true, 512, JSON_THROW_ON_ERROR),
            ];
        } catch(JsonException $exception) {
            return [];
        }
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
