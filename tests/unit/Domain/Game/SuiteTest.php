<?php

namespace Deck\Tests\unit\Domain\Game;

use Deck\Domain\Game\Suite;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class SuiteTest extends TestCase
{
    /** @test */
    public function validSuiteShouldReturnSuiteObject(): void
    {
        $suite = new Suite('diams');

        $this->assertEquals('diams', $suite->value());
    }

    /** @test */
    public function invalidSuiteShouldReturnException(): void
    {
        $invalidSuiteType = 'bastos';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Invalid suite type %s', $invalidSuiteType));

        $suite = new Suite($invalidSuiteType);
    }
}
