<?php

namespace Deck\Infrastructure\Serializer;

use Assert\Assertion as Assert;
use Broadway\Serializer\Serializable;
use Broadway\Serializer\SerializationException;
use Broadway\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;
use function class_implements;
use function get_class;
use function in_array;
use function json_encode;
use function sprintf;

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
        dump($object);
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

        if (!in_array(Serializable::class, class_implements($serializedObject['class']), true)) {
            throw new SerializationException(
                sprintf(
                    'Class \'%s\' does not implement Broadway\Serializer\Serializable',
                    $serializedObject['class']
                )
            );
        }

        return $this->serializer->deserialize(json_encode($serializedObject), $serializedObject['class'], 'json');
    }
}
