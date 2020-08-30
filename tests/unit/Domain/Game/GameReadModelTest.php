<?php

declare(strict_types=1);

namespace Deck\Tests\unit\Domain\Game;

use Deck\Domain\Game\GameId;
use Deck\Domain\Game\GameReadModel;
use Deck\Domain\User\PlayerId;
use PHPUnit\Framework\TestCase;

class GameReadModelTest extends TestCase
{
    /**
     * @test
     */
    public function given_game_id_and_players_should_create_game_read_model(): void
    {
        $gameId = GameId::create();
        $playerOne = PlayerId::create();
        $playerTwo = PlayerId::create();

        $game = new GameReadModel(
            $gameId,
            [
                $playerOne,
                $playerTwo,
            ]
        );

        self::assertEquals($gameId, $game->id());
        self::assertContains($playerOne, $game->players());
        self::assertContains($playerTwo, $game->players());
    }
}
