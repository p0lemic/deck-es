<?php

namespace Deck\Infrastructure\Serializer;

use Assert\Assertion as Assert;
use Broadway\Serializer\Serializer;
use JetBrains\PhpStorm\ArrayShape;
use function get_class;

class JsonSerializer implements Serializer
{
    #[ArrayShape(['class' => "string", 'payload' => "mixed"])]
    public function serialize($object): array
    {
        return [
            'class' => get_class($object),
            'payload' => $object->normalize(),
        ];
    }

    public function deserialize(array $serializedObject): mixed
    {
        Assert::keyExists($serializedObject, 'class', "Key 'class' should be set.");
        Assert::keyExists($serializedObject, 'payload', "Key 'payload' should be set.");

        $class = $serializedObject['class'];

        return $class::denormalize($serializedObject['payload']);
    }
}
