<?php

namespace Deck\Application\User;

use Deck\Domain\User\ValueObject\Auth\Credentials;
use Deck\Domain\User\ValueObject\Auth\HashedPassword;
use Deck\Domain\User\ValueObject\Email;

class SignUpCommand
{
    /** @var Credentials */
    public $credentials;

    public function __construct(
        string $email,
        string $plainPassword
    ) {
        $this->credentials = new Credentials(Email::fromString($email), HashedPassword::encode($plainPassword));
    }
}
