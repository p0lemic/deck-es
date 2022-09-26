<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Ui\Http\Controller;

use Deck\Application\Table\CreateTableCommand;
use Deck\Application\Table\JoinTableCommand;
use Deck\Infrastructure\User\Auth\Auth;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

class TableCreateController extends AbstractRenderController
{
    #[Route('/api/table', name: 'api.table.create', methods: ['POST'])]
    public function __invoke(Security $security): Response
    {
        /** @var Auth|null $user */
        $user = $security->getUser();

        $userId = $user?->id();

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
}
