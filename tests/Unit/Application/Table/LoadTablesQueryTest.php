<?php

namespace Deck\Tests\Unit\Application\Table;

use Deck\Application\Table\LoadTableQuery;
use Deck\Domain\Table\TableId;
use Deck\Domain\Table\TableReadModelRepositoryInterface;
use PHPUnit\Framework\TestCase;

class LoadTablesQueryTest extends TestCase
{
    private readonly LoadTableQuery $sut;
    private readonly TableReadModelRepositoryInterface $tableReadModelRepository;

    public function setUp(): void
    {
        $this->tableReadModelRepository = $this->createMock(TableReadModelRepositoryInterface::class);
        $this->sut = new LoadTableQuery($this->tableReadModelRepository);
    }

    public function testListAllTables(): void
    {
        $tableId = TableId::create();

        $this->tableReadModelRepository->expects($this->once())
            ->method('findByTableId')
            ->with($tableId);

        $this->sut->execute($tableId);
    }
}
