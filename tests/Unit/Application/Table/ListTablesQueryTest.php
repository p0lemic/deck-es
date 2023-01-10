<?php

namespace Deck\Tests\Unit\Application\Table;

use Deck\Application\Table\ListTablesQuery;
use Deck\Domain\Table\TableReadModelRepositoryInterface;
use PHPUnit\Framework\TestCase;

class ListTablesQueryTest extends TestCase
{
    private readonly ListTablesQuery $sut;
    private readonly TableReadModelRepositoryInterface $tableReadModelRepository;

    public function setUp(): void
    {
        $this->tableReadModelRepository = $this->createMock(TableReadModelRepositoryInterface::class);
        $this->sut = new ListTablesQuery($this->tableReadModelRepository);
    }

    public function testListAllTables(): void
    {
        $this->tableReadModelRepository->expects($this->once())
            ->method('all');

        $this->sut->execute();
    }
}
