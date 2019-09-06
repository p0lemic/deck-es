<?php

namespace Deck\Domain\Event;

interface EventStore
{
    public function append(Event $anEvent): void;
}
