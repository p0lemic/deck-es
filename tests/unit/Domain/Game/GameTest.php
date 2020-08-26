<?php

namespace Deck\Tests\unit\Domain\Game;

use Deck\Domain\Game\Game;
use Deck\Domain\Game\GameId;
use Deck\Domain\Game\Player;
use Deck\Domain\User\PlayerId;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    public function testNewDeckShouldHas40Cards(): void
    {
        $playerOne = PlayerId::create();
        $playerTwo = PlayerId::create();
        $players = [
            $playerOne,
            $playerTwo,
        ];

        $game = Game::create(GameId::create(), $players);

        self::assertCount(1, $game->getUncommittedEvents());
        self::assertCount(0, $game->deck()->cards());
    }
}
