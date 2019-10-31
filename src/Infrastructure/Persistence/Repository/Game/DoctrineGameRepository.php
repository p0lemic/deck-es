<?php

namespace Deck\Infrastructure\Persistence\Repository\Game;

use Deck\Domain\Game\Game;
use Deck\Domain\Game\GameRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bridge\Doctrine\RegistryInterface;

class DoctrineGameRepository extends ServiceEntityRepository implements GameRepositoryInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Game::class);
    }

    /**
     * @param Game $game
     *
     * @return void
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Game $game): void
    {
        $this->_em->persist($game);
        $this->_em->flush($game);
    }

    public function clearMemory(): void
    {
        $this->_em->clear(Game::class);
    }

    public function findByGameId(string $gameID): ?Game
    {
        return $this->findOneBy(
            [
                'id' => $gameID,
            ]
        );
    }

    public function findByTableId(string $tableId): ?Game
    {
        return $this->findOneBy(
            [
                'table_id' => $tableId,
            ]
        );
    }
}
