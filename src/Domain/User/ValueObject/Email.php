<?php

declare(strict_types=1);

namespace Deck\Domain\User\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;

class Email
{
    /** @var string */
    private string $value;

    private function __construct(string $email)
    {
        $this->value = $email;
    }

    /**
     * @param string $email
     * @return Email
     * @throws AssertionFailedException
     */
    public static function fromString(string $email): Email
    {
        Assertion::email($email, 'Email format is not valid');

        return new self($email);
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
