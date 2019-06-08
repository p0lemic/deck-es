<?php

namespace Deck\Tests\unit\Domain\Deck;

use Deck\Domain\Deck\Exception\InvalidSuiteException;
use Deck\Domain\Deck\Suite;
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

        $this->expectException(InvalidSuiteException::class);
        $this->expectExceptionMessage(sprintf('Invalid suite type %s', $invalidSuiteType));

        $suite = new Suite($invalidSuiteType);
    }
}
