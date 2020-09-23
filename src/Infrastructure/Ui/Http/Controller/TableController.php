<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Ui\Http\Controller;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Deck\Application\Table\CreateTableCommand;
use Deck\Application\Table\GetTableQuery;
use Deck\Application\Table\GetTablesQuery;
use Deck\Application\Table\JoinTableCommand;
use Deck\Domain\Shared\AggregateId;
use Deck\Domain\Table\Exception\PlayerAlreadyInTable;
use Deck\Domain\Table\Exception\TableIsFull;
use Deck\Domain\Table\TableId;
use Deck\Domain\User\PlayerId;
use InvalidArgumentException;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

class TableController extends AbstractRenderController
{
    /**
     * List available tables
     *
     * @SWG\Response(
     *     response=200,
     *     description="Available tables"
     * )
     *
     * @SWG\Tag(name="Table")
     *
     * @param GetTablesQuery $getTables
     * @return Response
     */
    public function list(GetTablesQuery $getTables): Response
    {
        $tables = $getTables->execute();

        return $this->createApiResponse($tables);
    }

    /**
     * Create new table
     *
     * @SWG\Response(
     *     response=201,
     *     description="Table created successfully"
     * )
     *
     * @SWG\Response(
     *     response=409,
     *     description="Conflict"
     * )
     *
     * @SWG\Tag(name="Table")
     *
     * @param Security $security
     * @return Response
     */
    public function create(Security $security): Response
    {
        try {
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
        } catch (PlayerAlreadyInTable $exception) {
            return $this->createApiResponse(['error' => $exception->getMessage()], Response::HTTP_CONFLICT);
        }
    }

    /**
     * Join a table
     *
     * @SWG\Response(
     *     response=200,
     *     description="Table joined successfully"
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Bad request"
     * )
     *
     * @SWG\Response(
     *     response=409,
     *     description="Conflict"
     * )
     *
     * @SWG\Parameter(
     *     name="table",
     *     type="object",
     *     in="body",
     *     schema=@SWG\Schema(type="object",
     *         @SWG\Property(property="id", type="string")
     *     )
     * )
     *
     * @SWG\Tag(name="Table")
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
        $tableId = $request->request->get('id');

        try {
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

            return $this->createApiResponse(['is_full' => $table->isFull()]);
        } catch (PlayerAlreadyInTable $exception) {
            return $this->createApiResponse(['error' => $exception->getMessage()], Response::HTTP_CONFLICT);
        } catch (InvalidArgumentException|AssertionFailedException|TableIsFull $exception) {
            return $this->createApiResponse(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
