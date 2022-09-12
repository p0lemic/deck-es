<?php

namespace Deck\Application\User;

use Deck\Application\Shared\Command\CommandInterface;
use Deck\Domain\User\PlayerId;
use Deck\Domain\User\ValueObject\Auth\Credentials;
use Deck\Domain\User\ValueObject\Auth\HashedPassword;
use Deck\Domain\User\ValueObject\Email;

class SignUpCommand implements CommandInterface
{
    private PlayerId $id;
    private Credentials $credentials;

    public function __construct(
        string $email,
        string $plainPassword
    ) {
        $this->id = PlayerId::create();
        $this->credentials = new Credentials(Email::fromString($email), HashedPassword::encode($plainPassword));
    }

    public function id(): PlayerId
    {
        return $this->id;
    }

    public function credentials(): Credentials
    {
        return $this->credentials;
    }
}
