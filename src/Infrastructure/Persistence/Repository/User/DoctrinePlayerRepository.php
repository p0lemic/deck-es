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
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\Mapping\MappingException;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Ramsey\Uuid\UuidInterface;

class DoctrinePlayerRepository extends ServiceEntityRepository implements PlayerReadModelRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlayerReadModel::class);
    }

    /**
     * @param PlayerId $playerId
     * @return PlayerReadModel
     */
    public function findById(PlayerId $playerId): ?PlayerReadModel
    {
        /** @var PlayerReadModel $player */
        $player = $this->findOneBy(['id' => $playerId]);

        return $player;
    }

    public function findByIdOrFail(PlayerId $playerId): PlayerReadModel
    {
        /** @var PlayerReadModel $user */
        $user = $this->findById($playerId);

        if (null === $user) {
            throw UserNotFoundException::idNotFound($playerId);
        }

        return $user;
    }

    public function findByEmailOrFail(Email $email): PlayerReadModel
    {
        /** @var PlayerReadModel $user */
        $user = $this->createQueryBuilder('user')
            ->where('user.credentials.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $user) {
            throw UserNotFoundException::emailNorFound($email);
        }

        return $user;
    }

    /**
     * @param Email $email
     * @return UuidInterface|null
     * @throws NonUniqueResultException
     */
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
     * @param Email $email
     * @return array
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

    /**
     * @param PlayerReadModel $player
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(PlayerReadModel $player): void
    {
        $this->_em->persist($player);
        $this->_em->flush($player);
    }

    /**
     * @return void
     * @throws MappingException
     */
    public function clearMemory(): void
    {
        $this->_em->clear(PlayerReadModel::class);
    }
}
