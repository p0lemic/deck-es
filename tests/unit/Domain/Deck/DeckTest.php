<?php

namespace Deck\Tests\unit\Domain\Deck;

use Deck\Domain\Game\Deck;
use Deck\Domain\Game\DeckId;
use Deck\Domain\Game\GameId;
use PHPUnit\Framework\TestCase;

class DeckTest extends TestCase
{
    /** @test */
    public function newDeckShouldHas40Cards(): void
    {
        $deck = Deck::create(DeckId::create(), GameId::create());
        $deck->shuffleCards();

        $this->assertCount(40, $deck->cards());
    }

    /** @test */
    public function whenPlayerDrawCardDeckShouldHaveOneLessCard(): void
    {
        $deck = new Deck(DeckId::create(), GameId::create());
        $deck->shuffleCards();

        $this->assertCount(40, $deck->cards());
        $deck->draw();
        $this->assertCount(39, $deck->cards());

        $totalCards = count($deck->cards());
        $deck->draw();
        $this->assertCount($totalCards - 1, $deck->cards());
    }
}
