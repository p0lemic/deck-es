<?php

namespace Deck\Tests\Unit\Application\Game;

use Deck\Application\Game\GamesListQuery;
use Deck\Domain\Game\GameReadModelRepositoryInterface;
use PHPUnit\Framework\TestCase;

class GamesListQueryTest extends TestCase
{
    private readonly GameReadModelRepositoryInterface $gameReadModelRepository;
    private readonly GamesListQuery $sut;

    public function setUp(): void
    {
        $this->gameReadModelRepository = $this->createMock(GameReadModelRepositoryInterface::class);
        $this->sut = new GamesListQuery($this->gameReadModelRepository);
    }

    public function testGetAllGames(): void
    {
        $this->gameReadModelRepository->expects($this->once())
            ->method('all');

        $this->sut->execute();
    }
}
