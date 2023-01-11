<?php

namespace Deck\Tests\Unit\Application\Game;

use Deck\Application\Game\ListGamesQuery;
use Deck\Domain\Game\GameReadModelRepositoryInterface;
use PHPUnit\Framework\TestCase;

class GamesListQueryTest extends TestCase
{
    private readonly GameReadModelRepositoryInterface $gameReadModelRepository;
    private readonly ListGamesQuery $sut;

    public function setUp(): void
    {
        $this->gameReadModelRepository = $this->createMock(GameReadModelRepositoryInterface::class);
        $this->sut = new ListGamesQuery($this->gameReadModelRepository);
    }

    public function testGetAllGames(): void
    {
        $this->gameReadModelRepository->expects($this->once())
            ->method('all');

        $this->sut->execute();
    }
}
