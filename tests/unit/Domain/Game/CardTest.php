<?php

namespace Deck\Tests\unit\Domain\Game;

use Deck\Domain\Game\Suite;
use Deck\Domain\Game\Rank;
use Deck\Domain\Game\Card;
use PHPUnit\Framework\TestCase;

class CardTest extends TestCase
{
    /** @test */
    public function validSuiteAndRankShouldReturnCardObject(): void
    {
        $card = new Card(new Suite('spades'), new Rank('J'));

        $this->assertEquals('spades', $card->suite()->value());
        $this->assertEquals('J', $card->rank()->value());
        $this->assertEquals('[J - spades]', $card->toString());
    }
}
