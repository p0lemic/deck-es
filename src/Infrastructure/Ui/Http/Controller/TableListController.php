<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Ui\Http\Controller;

use Deck\Application\Table\ListTablesQuery;
use Deck\Domain\Table\TableReadModel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TableListController extends AbstractRenderController
{
    #[Route('/api/table', name: 'api.table.list', methods: ['GET'])]
    public function __invoke(ListTablesQuery $getTables): Response
    {
        $tables = $getTables->execute();

        return (new JsonResponse())
            ->setEncodingOptions(JsonResponse::DEFAULT_ENCODING_OPTIONS | JSON_PRESERVE_ZERO_FRACTION)
            ->setData(
                array_map(
                    static fn (TableReadModel $table) => $table->normalize(),
                    $tables
                )
            );
    }
}
