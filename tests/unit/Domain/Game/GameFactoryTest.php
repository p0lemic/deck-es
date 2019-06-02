<?php

namespace Deck\Tests\unit\Domain\Game;

use Deck\Domain\Game\DeckFactory;
use Deck\Domain\Game\GameFactory;
use PHPUnit\Framework\TestCase;

class GameFactoryTest extends TestCase
{
    /** @test */
    public function newDeckShouldHas40Cards(): void
    {
        $deckFactory = new DeckFactory();
        $players = ['Player1', 'Player2'];

        $gameFactory = new GameFactory($deckFactory);
        $game = $gameFactory->createNewGame($players);

        $this->assertCount(40, $game->deck()->cards());

        $card = $game->playerDraw();

        $this->assertCount(39, $game->deck()->cards());
    }
}
