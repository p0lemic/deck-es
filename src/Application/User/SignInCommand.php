<?php

declare(strict_types=1);

namespace Deck\Application\User;

use Deck\Domain\User\ValueObject\Email;

class SignInCommand
{
    private Email $email;
    private string $plainPassword;

    public function __construct(string $email, string $plainPassword)
    {
        $this->email = Email::fromString($email);
        $this->plainPassword = $plainPassword;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function plainPassword(): string
    {
        return $this->plainPassword;
    }
}
