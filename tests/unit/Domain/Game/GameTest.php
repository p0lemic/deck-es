<?php

namespace Deck\Tests\unit\Domain\Game;

use Deck\Domain\Deck\Deck;
use Deck\Domain\Game\Game;
use Deck\Domain\User\Player;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    public function testNewDeckShouldHas40Cards(): void
    {
        $playerOne = Player::create('Player1');
        $playerTwo = Player::create('Player2');
        $players = [
            $playerOne,
            $playerTwo,
        ];

        /** @var Deck $deck */
        $deck = $this->createMock(Deck::class);

        $game = new Game($deck, $players);

        $this->assertCount(1, $game->getRecordedEvents());
    }
}
