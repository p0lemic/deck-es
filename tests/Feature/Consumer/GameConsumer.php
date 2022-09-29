<?php

declare(strict_types=1);

namespace Deck\Tests\Feature\Consumer;

use Deck\Domain\Game\GameId;
use Deck\Domain\Game\GameReadModel;
use Deck\Domain\User\PlayerId;
use GuzzleHttp\Client;

class GameConsumer
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getGameById(string $id): GameReadModel
    {
        $response = $this->client->get(
            "/api/game/" . $id,
            [
                "headers" => [
                    "Accept" => "application/json",
                ],
            ]
        );

        $body = json_decode($response->getBody()->getContents(), true);

        return new GameReadModel(
            GameId::fromString($body["id"]),
            array_map(
                static fn(array $player) => PlayerId::fromString($player['id']),
                $body["players"]
            )
        );
    }
}
