<?php

namespace Deck\Tests\unit\Domain\Game;

use Deck\Domain\Game\Game;
use Deck\Domain\Game\Player;
use Deck\Domain\User\PlayerId;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    public function testNewDeckShouldHas40Cards(): void
    {
        $playerOne = Player::create(PlayerId::create());
        $playerTwo = Player::create(PlayerId::create());
        $players = [
            $playerOne,
            $playerTwo,
        ];

        $game = Game::create($players);

        $this->assertCount(1, $game->getUncommittedEvents());
    }
}
