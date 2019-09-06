<?php

namespace Deck\Tests\unit\Domain\Deck;

use Deck\Domain\Deck\Suite;
use Deck\Domain\Deck\Rank;
use Deck\Domain\Deck\Card;
use PHPUnit\Framework\TestCase;

class CardTest extends TestCase
{
    /** @test */
    public function validSuiteAndRankShouldReturnCardObject(): void
    {
        $card = new Card(new Suite('spades'), new Rank('J'));

        $this->assertEquals('spades', $card->suite()->value());
        $this->assertEquals('J', $card->rank()->value());
        $this->assertEquals('J => spades', $card->__toString());
    }
}
