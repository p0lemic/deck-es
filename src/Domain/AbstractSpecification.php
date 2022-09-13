<?php

declare(strict_types=1);

namespace Deck\Domain;

use Deck\Domain\User\ValueObject\Email;

abstract class AbstractSpecification
{
    abstract public function isSatisfiedBy(Email $value): bool;

    final public function not(Email $value): bool
    {
        return !$this->isSatisfiedBy($value);
    }
}
