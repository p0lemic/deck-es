<?php

namespace Deck\Tests\Unit\Application\Table;

use Deck\Application\Table\JoinTableCommand;
use Deck\Application\Table\JoinTableHandler;
use Deck\Domain\Table\Table;
use Deck\Domain\Table\TableId;
use Deck\Domain\Table\TableRepositoryInterface;
use Deck\Domain\User\PlayerId;
use Deck\Domain\User\PlayerReadModel;
use Deck\Domain\User\PlayerReadModelRepositoryInterface;
use PHPUnit\Framework\TestCase;

class JoinTableHandlerTest extends TestCase
{
    private readonly JoinTableHandler $sut;
    private readonly TableRepositoryInterface $tableRepository;
    private readonly PlayerReadModelRepositoryInterface $playerReadModelRepository;

    public function setUp(): void
    {
        $this->tableRepository = $this->createMock(TableRepositoryInterface::class);
        $this->playerReadModelRepository = $this->createMock(PlayerReadModelRepositoryInterface::class);
        $this->sut = new JoinTableHandler($this->tableRepository, $this->playerReadModelRepository);
    }

    public function testCreateTable(): void
    {
        $tableId = TableId::create();
        $playerId = PlayerId::create();
        $player = $this->createMock(PlayerReadModel::class);
        $table = $this->createMock(Table::class);

        $command = new JoinTableCommand($tableId->value(), $playerId->value());

        $player->expects($this->once())
            ->method('id')
            ->willReturn($playerId);

        $this->playerReadModelRepository->expects($this->once())
            ->method('findByIdOrFail')
            ->with($playerId)
            ->willReturn($player);

        $this->tableRepository->expects($this->once())
            ->method('get')
            ->with($tableId)
            ->willReturn($table);

        $this->tableRepository->expects($this->once())
            ->method('store')
            ->with($table);

        $table->expects($this->once())
            ->method('playerSits')
            ->with($playerId);

        $this->sut->handle($command);
    }
}
