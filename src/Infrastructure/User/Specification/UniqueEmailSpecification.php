<?php

declare(strict_types=1);

namespace Deck\Infrastructure\User\Specification;

use Deck\Domain\AbstractSpecification;
use Deck\Domain\User\Exception\EmailAlreadyExistException;
use Deck\Domain\User\PlayerReadModelRepositoryInterface;
use Deck\Domain\User\Specification\UniqueEmailSpecificationInterface;
use Deck\Domain\User\ValueObject\Email;
use Doctrine\ORM\NonUniqueResultException;

final class UniqueEmailSpecification extends AbstractSpecification implements UniqueEmailSpecificationInterface
{
    private PlayerReadModelRepositoryInterface $playerRepository;

    public function __construct(PlayerReadModelRepositoryInterface $playerRepository)
    {
        $this->playerRepository = $playerRepository;
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
            if ($this->playerRepository->existsEmail($value)) {
                throw EmailAlreadyExistException::exists($value);
            }
        } catch (NonUniqueResultException $e) {
            throw EmailAlreadyExistException::exists($value);
        }

        return true;
    }
}
