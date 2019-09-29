<?php

declare(strict_types=1);

namespace Deck\Domain\User\Specification;

use Deck\Domain\User\Exception\EmailAlreadyExistException;
use Deck\Domain\User\ValueObject\Email;

interface UniqueEmailSpecificationInterface
{
    /**
     * @param Email $email
     * @return bool
     *
     * @throws EmailAlreadyExistException
     */
    public function isUnique(Email $email): bool;
}
