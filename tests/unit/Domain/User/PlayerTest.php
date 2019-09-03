<?php

declare(strict_types=1);

namespace Deck\Tests\unit\Domain\User;

use Deck\Domain\User\Player;
use PHPUnit\Framework\TestCase;

final class PlayerTest extends TestCase
{
    public function testCreateNewPlayer(): void
    {
        $sut = Player::createPlayerFromUsername('username');

        $this->assertEquals('username', $sut->username());
        $this->assertCount(0, $sut->hand());
    }
}
