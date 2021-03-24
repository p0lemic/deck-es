<?php

namespace Deck\Infrastructure\Persistence\Repository\Table;

use Deck\Domain\Table\Exception\TableNotFoundException;
use Deck\Domain\Table\TableId;
use Deck\Domain\Table\TableReadModel;
use Deck\Domain\Table\TableReadModelRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineTableReadModelRepository extends ServiceEntityRepository implements TableReadModelRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TableReadModel::class);
    }

    /**
     * @param TableReadModel $table
     *
     * @return void
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(TableReadModel $table): void
    {
        $this->_em->persist($table);
        $this->_em->flush($table);
    }

    public function clearMemory(): void
    {
        $this->_em->clear(TableReadModel::class);
    }

    public function findByTableId(TableId $tableId): ?TableReadModel
    {
        /** @var TableReadModel $table */
        $table = $this->findOneBy(
            [
                'id' => $tableId,
            ]
        );

        return $table;
    }

    public function findByTableIdOrFail(TableId $tableId): TableReadModel
    {
        /** @var TableReadModel $table */
        $table = $this->findOneBy(
            [
                'id' => $tableId,
            ]
        );

        if (null === $table) {
            throw TableNotFoundException::idNotFound($tableId);
        }

        return $table;
    }

    public function all(): array
    {
        return $this->findAll();
    }
}
