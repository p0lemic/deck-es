<?php

namespace Deck\Tests\unit\Domain\Game;

use Deck\Domain\Game\DeckFactory;
use Deck\Domain\Game\Game;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    /** @test */
    public function newDeckShouldHas40Cards(): void
    {
        $deckFactory = new DeckFactory();
        $players = ['Player1', 'Player2'];

        $game = new Game($deckFactory, $players);

        $this->assertCount(40, $game->deck()->cards());

        $card = $game->playerDraw();

        $this->assertCount(39, $game->deck()->cards());
    }
}
