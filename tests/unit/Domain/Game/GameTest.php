<?php

namespace Deck\Tests\unit\Domain\Game;

use Deck\Domain\Deck\DeckFactory;
use Deck\Domain\Game\Game;
use Deck\Domain\Game\GameId;
use Deck\Domain\User\Player;
use Deck\Domain\User\PlayerId;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    public function testNewDeckShouldHas40Cards(): void
    {
        $deckFactory = new DeckFactory();

        /** @var PlayerId $aPlayerOneId */
        $aPlayerOneId = $this->createMock(PlayerId::class);
        $playerOne = new Player($aPlayerOneId, 'Player1');

        /** @var PlayerId $aPlayerTwoId */
        $aPlayerTwoId = $this->createMock(PlayerId::class);
        $playerTwo = new Player($aPlayerTwoId, 'Player2');
        $players = [
            $playerOne,
            $playerTwo
        ];

        /** @var GameId $gameId */
        $gameId = $this->createMock(GameId::class);

        $game = new Game($gameId, $deckFactory, $players);

        $this->assertEquals($gameId->value(), $game->getAggregateId()->value());
        $this->assertCount(40, $game->deck()->cards());

        $game->playerDraw($playerOne);

        $this->assertCount(39, $game->deck()->cards());
    }
}
