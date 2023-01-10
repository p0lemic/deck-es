<?php

namespace Deck\Tests\Unit\Application\Game;

use Deck\Application\Game\DrawCardCommand;
use Deck\Application\Game\DrawCardHandler;
use Deck\Domain\Game\Game;
use Deck\Domain\Game\GameId;
use Deck\Domain\Game\GameRepositoryInterface;
use Deck\Domain\Game\Player;
use Deck\Domain\User\PlayerId;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\once;

class DrawCardHandlerTest extends TestCase
{
    private readonly GameRepositoryInterface $gameRepository;
    private readonly DrawCardHandler $sut;

    public function setUp(): void
    {
        $this->gameRepository = $this->createMock(GameRepositoryInterface::class);
        $this->sut = new DrawCardHandler($this->gameRepository);
    }

    public function testDrawCard(): void
    {
        $player = $this->createMock(Player::class);

        $game = $this->createMock(Game::class);
        $game->expects($this->once())
            ->method('getPlayer')
            ->willReturn($player);

        $game->expects($this->once())
            ->method('playerDraw')
            ->with($player);

        $this->gameRepository->expects($this->once())
            ->method('get')
            ->willReturn($game);

        $this->gameRepository->expects($this->once())
            ->method('store')
            ->with($game);

        $this->sut->handle(new DrawCardCommand(GameId::create(), PlayerId::create()));
    }
}
