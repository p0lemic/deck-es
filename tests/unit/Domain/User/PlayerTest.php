<?php

declare(strict_types=1);

namespace Deck\Tests\unit\Domain\User;

use Deck\Domain\User\Player;
use Deck\Domain\User\PlayerId;
use PHPUnit\Framework\TestCase;

final class PlayerTest extends TestCase
{
    public function testCreateNewPlayer(): void
    {
        /** @var PlayerId $aPlayerId */
        $aPlayerId = $this->createMock(PlayerId::class);
        $sut = new Player($aPlayerId, 'username');

        $this->assertEquals('username', $sut->username());
        $this->assertCount(0, $sut->hand());
    }
}
