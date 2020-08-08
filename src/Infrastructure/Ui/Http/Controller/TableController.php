<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Ui\Http\Controller;

use Deck\Application\Table\CreateTableCommand;
use Deck\Application\Table\GetTablesQuery;
use Deck\Application\Table\JoinTableCommand;
use Deck\Domain\User\PlayerId;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

class TableController extends AbstractRenderController
{
    /**
     * @param GetTablesQuery $getTables
     * @return Response
     */
    public function list(GetTablesQuery $getTables): Response
    {
        $tables = $getTables->execute();

        return $this->createApiResponse($tables);
    }

    /**
     * @param Security $security
     * @return Response
     */
    public function create(Security $security): Response
    {
        $user = $security->getUser();

        $userId = $user ? $user->id() : null;

        if (null === $userId) {
            throw new AuthenticationException('You should be logged in to create a new table.');
        }

        $this->execute(
            new CreateTableCommand(
                $userId
            )
        );

        return $this->createApiResponse(true, Response::HTTP_CREATED);
    }

    /**
     * @param Security $security
     * @param Request $request
     * @return Response
     */
    public function join(
        Security $security,
        Request $request
    ): Response {
        $tableId = $request->request->get('id');
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

        return $this->createApiResponse(true);
    }
}
