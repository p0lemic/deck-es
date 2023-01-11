<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Persistence\Repository\User;

use Deck\Domain\Shared\AggregateId;
use Deck\Domain\Table\Exception\TableNotFoundException;
use Deck\Domain\Table\TableReadModel;
use Deck\Domain\User\Exception\UserNotFoundException;
use Deck\Domain\User\PlayerId;
use Deck\Domain\User\PlayerReadModel;
use Deck\Domain\User\PlayerReadModelRepositoryInterface;
use Deck\Domain\User\ValueObject\Email;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;

class DBALPlayerReadModelRepository implements PlayerReadModelRepositoryInterface
{
    public function __construct(private Connection $connection)
    {
    }

    public function findById(PlayerId $playerId): PlayerReadModel
    {
        $query = <<<SQL
            SELECT *
            FROM deck.players
            WHERE id = :id
        SQL;

        $stmt = $this->connection->executeQuery(
            $query,
            [
                'id' => $playerId->value(),
            ],
            [
                'id' => ParameterType::STRING,
            ]
        );

        $player = $stmt->fetchAssociative();

        if (!$player) {
            throw UserNotFoundException::idNotFound($playerId);
        }

        /** @psalm-var array{id: string, created_at: string, updated_at: string, credentials_email: string, credentials_password: string} $player */
        return PlayerReadModel::denormalize(
            $player['id'],
            $player['credentials_email'],
            $player['credentials_password'],
            $player['created_at'],
            $player['updated_at'],
        );
    }

    public function findByEmailOrFail(Email $email): PlayerReadModel
    {
        $query = <<<SQL
            SELECT *
            FROM deck.players
            WHERE credentials_email = :email
        SQL;

        $stmt = $this->connection->executeQuery(
            $query,
            [
                'email' => $email->toString(),
            ],
            [
                'email' => ParameterType::STRING,
            ]
        );

        $player = $stmt->fetchAssociative();

        if (!$player) {
            throw UserNotFoundException::emailNotFound($email);
        }

        /** @psalm-var array{id: string, created_at: string, updated_at: string, credentials_email: string, credentials_password: string} $player */
        return PlayerReadModel::denormalize(
            $player['id'],
            $player['credentials_email'],
            $player['credentials_password'],
            $player['created_at'],
            $player['updated_at'],
        );
    }

    public function existsEmail(Email $email): ?AggregateId
    {
        $query = <<<SQL
            SELECT id
            FROM deck.players
            WHERE credentials_email = :email
        SQL;

        $stmt = $this->connection->executeQuery(
            $query,
            [
                'email' => $email->toString(),
            ],
            [
                'email' => ParameterType::STRING,
            ]
        );

        $player = $stmt->fetchAssociative();

        return isset($player['id']) ? PlayerId::fromString($player['id']) : null;
    }

    public function getCredentialsByEmail(Email $email): array
    {
        $user = $this->findByEmailOrFail($email);

        return [
            $user->id(),
            $user->email(),
            $user->hashedPassword(),
        ];
    }

    public function save(PlayerReadModel $player): void
    {
        $query = <<<SQL
            INSERT INTO deck.players (id, created_at, updated_at, credentials_email, credentials_password)
            VALUES (:id, :created_at, :updated_at, :credentials_email, :credentials_password)
        SQL;

        $this->connection->executeStatement(
            $query,
            [
                'id' => $player->id()->value(),
                'created_at' => $player->createdAt()->toString(),
                'updated_at' => $player->updatedAt()->toString(),
                'credentials_email' => $player->email(),
                'credentials_password' => $player->hashedPassword()
            ],
            [
                'id' => ParameterType::STRING,
                'created_at' => ParameterType::STRING,
                'updated_at' => ParameterType::STRING,
                'credentials_email' => ParameterType::STRING,
                'credentials_password' => ParameterType::STRING,
            ]
        );
    }

    public function update(PlayerReadModel $player): void
    {
        $query = <<<SQL
            UPDATE deck.players
            SET created_at = :created_at, updated_at = :updated_at, credentials_email = :credentials_email, credentials_password = :credentials_password
            WHERE id = :id
        SQL;

        $this->connection->executeStatement(
            $query,
            [
                'id' => $player->id()->value(),
                'created_at' => $player->createdAt()->toString(),
                'updated_at' => $player->updatedAt()->toString(),
                'credentials_email' => $player->email(),
                'credentials_password' => $player->hashedPassword()
            ],
            [
                'id' => ParameterType::STRING,
                'created_at' => ParameterType::STRING,
                'updated_at' => ParameterType::STRING,
                'credentials_email' => ParameterType::STRING,
                'credentials_password' => ParameterType::STRING,
            ]
        );
    }
}
