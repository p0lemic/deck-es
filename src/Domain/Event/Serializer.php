<?php

namespace Deck\Domain\Event;

interface Serializer
{
    public function serialize(DomainEvent $data): string;

    public function deserialize(string $data, string $type): DomainEvent;
}
