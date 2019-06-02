<?php

namespace Deck\Infrastructure\Persistence\Repository\Game;

use Deck\Domain\Game\Game;
use Deck\Domain\Game\GameRepositoryInterface;
use Doctrine\ORM\EntityRepository;

class DoctrineGameRepository extends EntityRepository implements GameRepositoryInterface
{
    public function save(Game $game): void
    {
        $this->_em->persist($game);
        $this->_em->flush($game);
    }

    public function clearMemory(): void
    {
        $this->_em->clear(User::class);
    }

    /**
     * @param int $gameID
     * @return Game
     */
    public function findByGameId(int $gameID): Game
    {
        return $this->findOneBy(
            [
                'id' => $gameID,
            ]
        );
    }

    /**
     * @param int $tableId
     * @return Game
     */
    public function findByTableId(int $tableId): Game
    {
        return $this->findOneBy(
            [
                'table_id' => $tableId,
            ]
        );
    }
}
