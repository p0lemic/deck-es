<?php

namespace Deck\Tests\unit\Domain\Game;

use Deck\Domain\Deck\DeckFactory;
use Deck\Domain\Game\GameFactory;
use Deck\Domain\User\Player;
use PHPUnit\Framework\TestCase;

class GameFactoryTest extends TestCase
{
    /** @test */
    public function newDeckShouldHas40Cards(): void
    {
        $deckFactory = new DeckFactory();
        $playerOne = new Player('Player1');
        $playerTwo = new Player('Player2');
        $players = [
            $playerOne,
            $playerTwo
        ];

        $gameFactory = new GameFactory($deckFactory);
        $game = $gameFactory->createNewGame($players);

        $this->assertCount(40, $game->deck()->cards());

        $game->playerDraw($playerOne);

        $this->assertCount(39, $game->deck()->cards());
    }
}
