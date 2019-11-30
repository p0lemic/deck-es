<?php

namespace Deck\Infrastructure\Persistence\Repository\Game;

use Deck\Domain\Game\Game;
use Deck\Domain\Game\GameReadModel;
use Deck\Domain\Game\GameReadModelRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bridge\Doctrine\RegistryInterface;

class DoctrineGameReadModelRepository extends ServiceEntityRepository implements GameReadModelRepositoryInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GameReadModel::class);
    }

    /**
     * @param GameReadModel $game
     *
     * @return void
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(GameReadModel $game): void
    {
        $this->_em->persist($game);
        $this->_em->flush($game);
    }

    public function clearMemory(): void
    {
        $this->_em->clear(GameReadModel::class);
    }

    public function findByGameId(string $gameID): ?GameReadModel
    {
        return $this->findOneBy(
            [
                'id' => $gameID,
            ]
        );
    }

    public function findByTableId(string $tableId): ?GameReadModel
    {
        return $this->findOneBy(
            [
                'table_id' => $tableId,
            ]
        );
    }
}
