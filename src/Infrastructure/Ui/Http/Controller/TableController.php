<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Ui\Http\Controller;

use Assert\Assertion;
use Deck\Application\Table\CreateTableCommand;
use Deck\Application\Table\GetTableQuery;
use Deck\Application\Table\GetTablesQuery;
use Deck\Application\Table\JoinTableCommand;
use Deck\Domain\Shared\AggregateId;
use Deck\Domain\Table\TableId;
use Deck\Domain\Table\TableReadModel;
use Deck\Domain\User\PlayerId;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

class TableController extends AbstractRenderController
{
    /**
     * List available tables
     *
     * @OA\Response(
     *     response=200,
     *     description="Available tables"
     * )
     *
     * @OA\Tag(name="Table")
     *
     * @param GetTablesQuery $getTables
     * @return Response
     */
    public function list(GetTablesQuery $getTables): Response
    {
        $tables = $getTables->execute();

        return (new JsonResponse())
            ->setEncodingOptions(JsonResponse::DEFAULT_ENCODING_OPTIONS | JSON_PRESERVE_ZERO_FRACTION)
            ->setData(
                array_map(
                    static fn(TableReadModel $table) => $table->toArray(),
                    $tables
                )
            );
    }

    /**
     * Create new table
     *
     * @OA\Response(
     *     response=201,
     *     description="Table created successfully"
     * )
     *
     * @OA\Response(
     *     response=409,
     *     description="Conflict"
     * )
     *
     * @OA\Tag(name="Table")
     *
     * @param Security $security
     * @return Response
     */
    public function create(Security $security): Response
    {
        $user = $security->getUser();

        /** @var AggregateId $userId */
        $userId = $user ? $user->id() : null;

        if (null === $userId) {
            throw new AuthenticationException('You should be logged in to create a new table.');
        }

        $createTableCommand = new CreateTableCommand();

        $this->execute($createTableCommand);

        $this->execute(
            new JoinTableCommand(
                $createTableCommand->id()->value(),
                $userId->value()
            )
        );

        return $this->createApiResponse(['id' => $createTableCommand->id()->value()], Response::HTTP_CREATED);
    }

    /**
     * Join a table
     *
     * @OA\Response(
     *     response=200,
     *     description="Table joined successfully"
     * )
     *
     * @OA\Response(
     *     response=400,
     *     description="Bad request"
     * )
     *
     * @OA\Response(
     *     response=409,
     *     description="Conflict"
     * )
     *
     * @OA\RequestBody(
     *     request="table",
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/Table"),
     * )
     *
     * @OA\Tag(name="Table")
     *
     * @param Security $security
     * @param GetTableQuery $getTableQuery
     * @param Request $request
     * @return Response
     */
    public function join(
        Security $security,
        GetTableQuery $getTableQuery,
        Request $request
    ): Response {
        $tableId = $request->request->getDigits('id');

        Assertion::notNull($tableId, 'Table id can\'t be empty');

        $user = $security->getUser();

        /** @var PlayerId $userId */
        $userId = $user ? $user->id() : null;

        if (null === $userId) {
            throw new AuthenticationException('You should be logged in to join a table.');
        }

        $this->execute(
            new JoinTableCommand(
                $tableId,
                $userId->value()
            )
        );

        $table = $getTableQuery->execute(TableId::fromString($tableId));

        return $this->createApiResponse(['joined' => $table->isFull()]);
    }
}
