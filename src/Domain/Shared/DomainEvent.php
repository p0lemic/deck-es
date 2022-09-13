<?php

declare(strict_types=1);

namespace Deck\Domain\Shared;

use Deck\Domain\Shared\ValueObject\DateTime;

interface DomainEvent
{
    public function occurredOn(): DateTime;

    public function normalize(): array;

    public static function denormalize(array $payload): self;
}
