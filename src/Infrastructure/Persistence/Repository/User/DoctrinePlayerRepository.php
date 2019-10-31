<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Persistence\Repository\User;

use Deck\Domain\User\Player;
use Deck\Domain\User\PlayerId;
use Deck\Domain\User\PlayerRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\Mapping\MappingException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
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
