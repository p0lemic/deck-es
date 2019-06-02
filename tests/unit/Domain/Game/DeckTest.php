<?php

namespace Deck\Tests\unit\Domain\Game;

use Deck\Domain\Game\Deck;
use PHPUnit\Framework\TestCase;

class DeckTest extends TestCase
{
    /** @test */
    public function newDeckShouldHas40Cards(): void
    {
        $deck = new Deck();

        $this->assertCount(40, $deck->cards());
    }

    /** @test */
    public function whenPlayerDrawCardDeckShouldHaveOneLessCard(): void
    {
        $deck = new Deck();

        $totalCards = count($deck->cards());
        $deck->draw();

        $this->assertCount($totalCards - 1, $deck->cards());
    }
}
