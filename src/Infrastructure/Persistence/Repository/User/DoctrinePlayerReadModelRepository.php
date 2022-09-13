<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Persistence\Repository\User;

use Deck\Domain\Shared\AggregateId;
use Deck\Domain\User\Exception\UserNotFoundException;
use Deck\Domain\User\PlayerId;
use Deck\Domain\User\PlayerReadModel;
use Deck\Domain\User\PlayerReadModelRepositoryInterface;
use Deck\Domain\User\ValueObject\Email;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

class DoctrinePlayerReadModelRepository extends ServiceEntityRepository implements PlayerReadModelRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlayerReadModel::class);
    }

    public function findById(PlayerId $playerId): ?PlayerReadModel
    {
        /** @var PlayerReadModel $player */
        $player = $this->findOneBy(['id' => $playerId]);

        return $player;
    }

    /** @throws UserNotFoundException */
    public function findByIdOrFail(PlayerId $playerId): PlayerReadModel
    {
        $user = $this->findById($playerId);

        if (null === $user) {
            throw UserNotFoundException::idNotFound($playerId);
        }

        return $user;
    }

    /**
     * @throws NonUniqueResultException
     * @throws UserNotFoundException
     */
    public function findByEmailOrFail(Email $email): PlayerReadModel
    {
        /** @var PlayerReadModel|null $user */
        $user = $this->createQueryBuilder('user')
            ->where('user.credentials.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $user) {
            throw UserNotFoundException::emailNotFound($email);
        }

        return $user;
    }

    /** @throws NonUniqueResultException */
    public function existsEmail(Email $email): ?AggregateId
    {
        $userId = $this->createQueryBuilder('user')
            ->select('user.id')
            ->where('user.credentials.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->setHydrationMode(AbstractQuery::HYDRATE_ARRAY)
            ->getOneOrNullResult();

        return $userId['id'] ?? null;
    }

    /**
     * @throws NonUniqueResultException
     * @throws UserNotFoundException
     */
    public function getCredentialsByEmail(Email $email): array
    {
        $user = $this->findByEmailOrFail($email);

        return [
            $user->id(),
            $user->email(),
            $user->hashedPassword(),
        ];
    }

    public function save(PlayerReadModel $player): void
    {
        $this->_em->persist($player);
        $this->_em->flush();
    }

    public function clearMemory(): void
    {
        $this->_em->clear();
    }
}
