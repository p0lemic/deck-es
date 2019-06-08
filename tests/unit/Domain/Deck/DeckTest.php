<?php

namespace Deck\Tests\unit\Domain\Deck;

use Deck\Domain\Deck\Deck;
use Deck\Domain\Deck\Exception\DeckCardsNumberException;
use Deck\Domain\Deck\DeckId;
use PHPUnit\Framework\TestCase;

class DeckTest extends TestCase
{
    /** @test
     * @throws DeckCardsNumberException
     */
    public function newDeckShouldHas40Cards(): void
    {
        $deck = new Deck(DeckId::create());

        $this->assertCount(40, $deck->cards());
    }

    /** @test
     * @throws DeckCardsNumberException
     */
    public function whenPlayerDrawCardDeckShouldHaveOneLessCard(): void
    {
        $deck = new Deck(DeckId::create());

        $totalCards = count($deck->cards());
        $deck->draw();

        $this->assertCount($totalCards - 1, $deck->cards());
    }
}
