<?php

declare(strict_types=1);

namespace Deck\Infrastructure\User\Specification;

use Deck\Domain\AbstractSpecification;
use Deck\Domain\User\CheckUserByEmailInterface;
use Deck\Domain\User\Exception\EmailAlreadyExistException;
use Deck\Domain\User\Specification\UniqueEmailSpecificationInterface;
use Deck\Domain\User\ValueObject\Email;
use Doctrine\ORM\NonUniqueResultException;

final class UniqueEmailSpecification extends AbstractSpecification implements UniqueEmailSpecificationInterface
{
    /** @var CheckUserByEmailInterface */
    private $checkUserByEmail;

    public function __construct(CheckUserByEmailInterface $checkUserByEmail)
    {
        $this->checkUserByEmail = $checkUserByEmail;
    }

    /**
     * @param Email $email
     * @return bool
     * @throws EmailAlreadyExistException
     */
    public function isUnique(Email $email): bool
    {
        return $this->isSatisfiedBy($email);
    }

    public function isSatisfiedBy(Email $value): bool
    {
        try {
            if ($this->checkUserByEmail->existsEmail($value)) {
                throw EmailAlreadyExistException::exists($value);
            }
        } catch (NonUniqueResultException $e) {
            throw EmailAlreadyExistException::exists($value);
        }

        return true;
    }
}
