<?php

namespace Deck\Tests\Unit\Application\Game;

use Deck\Application\Game\ListGamesQuery;
use Deck\Application\Game\LoadGameQuery;
use Deck\Domain\Game\GameId;
use Deck\Domain\Game\GameReadModelRepositoryInterface;
use PHPUnit\Framework\TestCase;

class LoadGameQueryTest extends TestCase
{
    private readonly GameReadModelRepositoryInterface $gameReadModelRepository;
    private readonly LoadGameQuery $sut;

    public function setUp(): void
    {
        $this->gameReadModelRepository = $this->createMock(GameReadModelRepositoryInterface::class);
        $this->sut = new LoadGameQuery($this->gameReadModelRepository);
    }

    public function testGetAllGames(): void
    {
        $gameId = GameId::create();

        $this->gameReadModelRepository->expects($this->once())
            ->method('findByGameId')
            ->with($gameId);

        $this->sut->execute($gameId);
    }
}
