<?php

namespace Deck\Infrastructure\Persistence\Repository\Game;

use Deck\Domain\Game\GameId;
use Deck\Domain\Game\GameReadModel;
use Deck\Domain\Game\GameReadModelRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class DoctrineGameReadModelRepository extends ServiceEntityRepository implements GameReadModelRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
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

    public function findByGameId(GameId $gameID): ?GameReadModel
    {
        /** @var GameReadModel $game */
        $game = $this->findOneBy(
            [
                'id' => $gameID,
            ]
        );

        return $game;
    }

    public function all(): array
    {
        return $this->findAll();
    }
}
