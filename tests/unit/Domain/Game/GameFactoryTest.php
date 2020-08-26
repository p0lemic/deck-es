<?php

namespace Deck\Tests\unit\Domain\Game;

use Deck\Domain\Game\GameFactory;
use Deck\Domain\Game\GameId;
use Deck\Domain\Game\Player;
use Deck\Domain\User\PlayerId;
use PHPUnit\Framework\TestCase;

class GameFactoryTest extends TestCase
{
    /** @test */
    public function newDeckShouldHas40Cards(): void
    {
        $playerOne = Player::create(PlayerId::create());
        $playerTwo = Player::create(PlayerId::create());
        $players = [
            $playerOne->playerId(),
            $playerTwo->playerId(),
        ];

        $gameFactory = new GameFactory();
        $game = $gameFactory->createNewGame(GameId::create(), $players);
        $game->initGame();

        $this->assertCount(40, $game->deck()->cards());

        $game->playerDraw($playerOne);

        $this->assertCount(39, $game->deck()->cards());
    }
}
