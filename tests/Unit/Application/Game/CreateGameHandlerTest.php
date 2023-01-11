<?php

namespace Deck\Tests\Unit\Application\Game;

use Deck\Application\Game\CreateGameCommand;
use Deck\Application\Game\CreateGameHandler;
use Deck\Domain\Game\Brisca;
use Deck\Domain\Game\Exception\InvalidPlayerNumberException;
use Deck\Domain\Game\Game;
use Deck\Domain\Game\GameFactory;
use Deck\Domain\Game\GameRepositoryInterface;
use Deck\Domain\Table\TableId;
use Deck\Domain\Table\TableReadModel;
use Deck\Domain\Table\TableReadModelRepositoryInterface;
use Deck\Domain\User\PlayerId;
use Deck\Domain\User\PlayerReadModelRepositoryInterface;
use PHPUnit\Framework\TestCase;

class CreateGameHandlerTest extends TestCase
{
    private readonly TableReadModelRepositoryInterface $tableRepository;
    private readonly PlayerReadModelRepositoryInterface $playerRepository;
    private readonly GameRepositoryInterface $gameRepository;
    private readonly GameFactory $gameFactory;
    private readonly CreateGameHandler $sut;

    public function setUp(): void
    {
        $this->tableRepository = $this->createMock(TableReadModelRepositoryInterface::class);
        $this->playerRepository = $this->createMock(PlayerReadModelRepositoryInterface::class);
        $this->gameRepository = $this->createMock(GameRepositoryInterface::class);
        $this->gameFactory = $this->createMock(GameFactory::class);

        $this->sut = new CreateGameHandler($this->gameFactory, $this->gameRepository, $this->playerRepository, $this->tableRepository);
    }

    public function testCreateGame(): void
    {
        $tableId = TableId::create();

        $table = $this->createMock(TableReadModel::class);
        $table->expects($this->once())
            ->method('isFull')
            ->willReturn(true);

        $table->expects($this->once())
            ->method('players')
            ->willReturn(
                [
                    PlayerId::create(),
                    PlayerId::create(),
                ]
            );

        $this->tableRepository->expects($this->once())
            ->method('findByTableId')
            ->with($tableId)
            ->willReturn($table);

        $this->playerRepository->expects($this->exactly(2))
            ->method('findById');

        $game = $this->createMock(Game::class);

        $this->gameFactory->expects($this->once())
            ->method('createNewGame')
            ->willReturn($game);

        $this->gameRepository->expects($this->once())
            ->method('store')
            ->with($game);

        $this->sut->handle(new CreateGameCommand($tableId, new Brisca()));
    }

    public function testCreateGameWithoutFullTable(): void
    {
        $this->expectException(InvalidPlayerNumberException::class);
        $this->expectExceptionMessage('The Table should have all seats filled by players');

        $tableId = TableId::create();

        $table = $this->createMock(TableReadModel::class);
        $table->expects($this->once())
            ->method('isFull')
            ->willReturn(false);

        $this->tableRepository->expects($this->once())
            ->method('findByTableId')
            ->with($tableId)
            ->willReturn($table);

        $this->sut->handle(new CreateGameCommand($tableId, new Brisca()));
    }
}
