<?php

declare(strict_types=1);

namespace Deck\Domain\User\ValueObject\Auth;

use Deck\Domain\User\ValueObject\Email;

class Credentials
{
    /** @var Email */
    private $email;
    /** @var HashedPassword */
    private $password;

    public function __construct(Email $email, HashedPassword $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function password(): HashedPassword
    {
        return $this->password;
    }
}
