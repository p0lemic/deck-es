<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Ui\Http\Controller;

use Assert\Assertion;
use Deck\Application\Table\GetTableQuery;
use Deck\Application\Table\JoinTableCommand;
use Deck\Infrastructure\User\Auth\Auth;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

class TableJoinController extends AbstractRenderController
{
    #[Route('/api/table/join', name: 'api.table.join', methods: ['POST'])]
    public function __invoke(
        Security $security,
        GetTableQuery $getTableQuery,
        Request $request
    ): Response {
        $tableId = $request->request->get('id');

        Assertion::notNull($tableId, 'Table id can\'t be empty');
        Assertion::string($tableId);

        /** @var Auth|null $user */
        $user = $security->getUser();

        $userId = $user?->id();

        if (null === $userId) {
            throw new AuthenticationException('You should be logged in to join a table.');
        }

        $this->execute(
            new JoinTableCommand(
                $tableId,
                $userId->value()
            )
        );

        return $this->createApiResponse([]);
    }
}
