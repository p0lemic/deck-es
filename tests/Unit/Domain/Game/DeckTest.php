<?php

namespace Deck\Tests\Unit\Domain\Game;

use Deck\Domain\Game\Deck;
use Deck\Domain\Game\DeckId;
use PHPUnit\Framework\TestCase;

class DeckTest extends TestCase
{
    /** @test */
    public function newDeckShouldHasZeroCards(): void
    {
        $deckId = DeckId::create();

        $deck = Deck::create($deckId);
        self::assertEquals($deckId, $deck->id());
        self::assertCount(0, $deck->cards());
    }
}
