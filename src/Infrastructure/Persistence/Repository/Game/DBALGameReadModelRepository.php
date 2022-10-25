<?php

namespace Deck\Infrastructure\Persistence\Repository\Game;

use Deck\Domain\Game\Exception\GameNotFoundException;
use Deck\Domain\Game\GameId;
use Deck\Domain\Game\GameReadModel;
use Deck\Domain\Game\GameReadModelRepositoryInterface;
use Deck\Domain\Game\Player;
use Deck\Domain\User\PlayerId;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;

class DBALGameReadModelRepository implements GameReadModelRepositoryInterface
{
    public function __construct(private Connection $connection)
    {
    }

    public function save(GameReadModel $game): void
    {
        $query = <<<SQL
            INSERT INTO deck.games (id, players, current_player, deck, cards_on_table)
            VALUES (:id, :players, :current_player, :deck, :cards_on_table)
        SQL;

        $this->connection->executeStatement(
            $query,
            [
                'id' => $game->id(),
                'players' => json_encode($game->players()),
                'current_player' => $game->currentPlayerId(),
                'deck' => json_encode($game->deck()),
                'cards_on_table' => json_encode($game->cardsOnTable()),
            ],
            [
                'id' => ParameterType::STRING,
                'players' => ParameterType::STRING,
                'current_player' => ParameterType::STRING,
                'deck' => ParameterType::STRING,
                'cards_on_table' => ParameterType::STRING,
            ]
        );
    }

    public function update(GameReadModel $gameReadModel): void
    {
        $query = <<<SQL
            UPDATE deck.games 
            SET players = :players, current_player = :current_player, deck = :deck, cards_on_table = :cards_on_table
            WHERE id = :id
        SQL;

        $this->connection->executeStatement(
            $query,
            [
                'id' => $gameReadModel->id(),
                'players' => json_encode($gameReadModel->players()),
                'current_player' => $gameReadModel->currentPlayerId(),
                'deck' => json_encode($gameReadModel->deck()),
                'cards_on_table' => json_encode($gameReadModel->cardsOnTable()),
            ],
            [
                'id' => ParameterType::STRING,
                'players' => ParameterType::STRING,
                'current_player' => ParameterType::STRING,
                'deck' => ParameterType::STRING,
                'cards_on_table' => ParameterType::STRING,
            ]
        );
    }

    public function findByGameId(GameId $gameId): GameReadModel
    {
        $query = <<<SQL
            SELECT *
            FROM deck.games
            WHERE id = :id
        SQL;

        $stmt = $this->connection->executeQuery(
            $query,
            [
                'id' => $gameId->value(),
            ],
            [
                'id' => ParameterType::STRING,
            ]
        );

        $game = $stmt->fetchAssociative();

        if (!$game) {
            throw GameNotFoundException::idNotFound($gameId);
        }

        /** @psalm-var array{id: string, players: string, current_player: string, deck: string, cards_on_table: string} $game */
        return GameReadModel::denormalize(
            $game['id'],
            json_decode($game['players']),
            $game['current_player'],
            json_decode($game['deck']),
            json_decode($game['cards_on_table'])
        );
    }

    /** @return GameReadModel[] */
    public function all(): array
    {
        $query = <<<SQL
            SELECT *
            FROM deck.games
        SQL;

        $stmt = $this->connection->executeQuery($query);

        $gamesData = $stmt->fetchAllAssociative();

        $games = [];
        foreach ($gamesData as $game) {
            /** @psalm-var array{id: string, players: string, current_player: string, deck: string, cards_on_table: string} $game */
            $games[] = GameReadModel::denormalize(
                $game['id'],
                json_decode($game['players']),
                $game['current_player'],
                json_decode($game['deck']),
                json_decode($game['cards_on_table'])
            );
        }

        return $games;
    }
}
