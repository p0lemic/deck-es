<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Persistence\Repository\Table;

use Deck\Domain\Table\Exception\TableNotFoundException;
use Deck\Domain\Table\TableId;
use Deck\Domain\Table\TableReadModel;
use Deck\Domain\Table\TableReadModelRepositoryInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;

class DBALTableReadModelRepository implements TableReadModelRepositoryInterface
{
    public function __construct(private Connection $connection)
    {
    }

    public function findByTableId(TableId $tableId): TableReadModel
    {
        $query = <<<SQL
            SELECT *
            FROM deck.tables
            WHERE id = :id
        SQL;

        $stmt = $this->connection->executeQuery(
            $query,
            [
                'id' => $tableId->value(),
            ],
            [
                'id' => ParameterType::STRING,
            ]
        );

        $table = $stmt->fetchAssociative();

        if (!$table) {
            throw TableNotFoundException::idNotFound($tableId);
        }

        /** @psalm-var array{id: string, players: string} $table */
        return TableReadModel::denormalize(
            $table['id'],
            json_decode($table['players']),
        );
    }

    public function all(): array
    {
        $query = <<<SQL
            SELECT *
            FROM deck.tables
        SQL;

        $stmt = $this->connection->executeQuery($query);

        $tablesData = $stmt->fetchAllAssociative();

        $tables = [];
        foreach ($tablesData as $table) {
            /** @psalm-var array{id: string, players: string} $table */
            $tables[] = TableReadModel::denormalize(
                $table['id'],
                json_decode($table['players']),
            );
        }

        return $tables;
    }

    public function save(TableReadModel $table): void
    {
        $query = <<<SQL
            INSERT INTO deck.tables (id, players)
            VALUES (:id, :players)
        SQL;

        $normalizedTable = $table->normalize();

        $this->connection->executeStatement(
            $query,
            [
                'id' => $normalizedTable['id'],
                'players' => json_encode($normalizedTable['players']),
            ],
            [
                'id' => ParameterType::STRING,
                'players' => ParameterType::STRING,
            ]
        );
    }

    public function update(TableReadModel $table): void
    {
        $query = <<<SQL
            UPDATE deck.tables 
            SET players = :players
            WHERE id = :id
        SQL;

        $normalizedTable = $table->normalize();

        $this->connection->executeStatement(
            $query,
            [
                'id' => $normalizedTable['id'],
                'players' => json_encode($normalizedTable['players']),
            ],
            [
                'id' => ParameterType::STRING,
                'players' => ParameterType::STRING,
            ]
        );
    }
}
