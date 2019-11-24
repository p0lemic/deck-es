<?php

declare(strict_types=1);

namespace Deck\Domain\User\ValueObject;

use Assert\Assertion;

class Email
{
    /** @var string */
    private $value;

    /**
     * @param string $email
     * @return Email
     */
    public static function fromString(string $email): self
    {
        Assertion::email($email, 'Not a valid email');

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
