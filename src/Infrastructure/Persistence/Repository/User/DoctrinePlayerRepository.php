<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Persistence\Repository\User;

use Deck\Domain\Aggregate\AggregateId;
use Deck\Domain\User\Exception\UserNotFoundException;
use Deck\Domain\User\Player;
use Deck\Domain\User\PlayerId;
use Deck\Domain\User\PlayerRepositoryInterface;
use Deck\Domain\User\ValueObject\Email;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\Mapping\MappingException;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

class DoctrinePlayerRepository extends ServiceEntityRepository implements PlayerRepositoryInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Player::class);
    }

    /**
     * @param PlayerId $playerId
     * @return Player
     */
    public function findById(PlayerId $playerId): ?Player
    {
        /** @var Player $player */
        $player = $this->findOneBy(['id' => $playerId]);

        return $player;
    }

    public function findByIdOrFail(PlayerId $playerId): Player
    {
        /** @var Player $user */
        $user = $this->findById($playerId);

        if (null === $user) {
            throw UserNotFoundException::idNotFound($playerId);
        }

        return $user;
    }

    public function findByEmailOrFail(Email $email): Player
    {
        /** @var Player $user */
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
     * @param Player $player
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Player $player): void
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
        $this->_em->clear(Player::class);
    }
}
