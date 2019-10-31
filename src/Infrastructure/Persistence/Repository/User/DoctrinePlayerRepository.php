<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Persistence\Repository\User;

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
        return $this->findOneBy(['id' => $playerId]);
    }

    /**
     * @param Email $email
     * @return UuidInterface|null
     * @throws NonUniqueResultException
     */
    public function existsEmail(Email $email): ?UuidInterface
    {
        $userId = $this->createQueryBuilder('user')
            ->select('user.id')
            ->where('user.credentials.email = :email')
            ->setParameter('email', (string) $email)
            ->getQuery()
            ->setHydrationMode(AbstractQuery::HYDRATE_ARRAY)
            ->getOneOrNullResult()
        ;

        return $userId['uuid'] ?? null;
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
