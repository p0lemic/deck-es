<?php

namespace Deck\Tests\Unit\Domain\Game;

use Deck\Domain\Game\Exception\InvalidRankException;
use Deck\Domain\Game\Rank;
use PHPUnit\Framework\TestCase;

class RankTest extends TestCase
{
    /** @test */
    public function validRankShouldReturnRankObject(): void
    {
        $rank = new Rank('K');

        $this->assertEquals('K', $rank->value());
    }

    /** @test */
    public function invalidRankShouldReturnException(): void
    {
        $invalidRankType = 13;

        $this->expectException(InvalidRankException::class);
        $this->expectExceptionMessage(sprintf('Invalid rank type %s', $invalidRankType));

        $suite = new Rank($invalidRankType);
    }
}
