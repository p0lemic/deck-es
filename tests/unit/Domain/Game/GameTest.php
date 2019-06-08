<?php

namespace Deck\Tests\unit\Domain\Game;

use Deck\Domain\Deck\DeckFactory;
use Deck\Domain\Game\Game;
use Deck\Domain\Game\GameId;
use Deck\Domain\User\Player;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    public function testNewDeckShouldHas40Cards(): void
    {
        $deckFactory = new DeckFactory();
        $playerOne = new Player('Player1');
        $playerTwo = new Player('Player2');
        $players = [
            $playerOne,
            $playerTwo
        ];

        /** @var GameId $gameId */
        $gameId = $this->createMock(GameId::class);

        $game = new Game($gameId, $deckFactory, $players);

        $this->assertEquals($gameId->value(), $game->id()->value());
        $this->assertCount(40, $game->deck()->cards());

        $game->playerDraw($playerOne);

        $this->assertCount(39, $game->deck()->cards());
    }
}
