<?php

namespace Deck\Tests\Unit\Application\Table;

use Deck\Application\Table\CreateTableCommand;
use Deck\Application\Table\CreateTableHandler;
use Deck\Domain\Table\TableRepositoryInterface;
use PHPUnit\Framework\TestCase;

class CreateTableHandlerTest extends TestCase
{
    private readonly CreateTableHandler $sut;
    private readonly TableRepositoryInterface $tableRepository;

    public function setUp(): void
    {
        $this->tableRepository = $this->createMock(TableRepositoryInterface::class);
        $this->sut = new CreateTableHandler($this->tableRepository);
    }

    public function testCreateTable(): void
    {
        $command = new CreateTableCommand();

        $this->tableRepository->expects($this->once())
            ->method('store');

        $this->sut->handle($command);
    }
}
