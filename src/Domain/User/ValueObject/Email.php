<?php

declare(strict_types=1);

namespace Deck\Domain\User\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;

class Email
{
    /** @var string */
    private string $value;

    /**
     * @param string $email
     * @return Email
     * @throws AssertionFailedException
     */
    public static function fromString(string $email): Email
    {
        Assertion::email($email, 'Email format is not valid');

        $mail = new self();

        $mail->value = $email;

        return $mail;
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
