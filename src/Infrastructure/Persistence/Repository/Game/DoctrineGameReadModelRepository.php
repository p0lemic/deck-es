<?php

namespace Deck\Infrastructure\Persistence\Repository\Game;

use Deck\Domain\Game\GameId;
use Deck\Domain\Game\GameReadModel;
use Deck\Domain\Game\GameReadModelRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineGameReadModelRepository extends ServiceEntityRepository implements GameReadModelRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameReadModel::class);
    }

    public function save(GameReadModel $game): void
    {
        $this->_em->persist($game);
        $this->_em->flush();
    }

    public function clearMemory(): void
    {
        $this->_em->clear();
    }

    public function findByGameId(GameId $gameId): ?GameReadModel
    {
        /** @var GameReadModel $game */
        $game = $this->findOneBy(
            [
                'id' => $gameId,
            ]
        );

        return $game;
    }

    public function all(): array
    {
        return $this->findAll();
    }
}
