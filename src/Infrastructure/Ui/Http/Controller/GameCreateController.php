<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Ui\Http\Controller;

use Assert\Assertion;
use Deck\Application\Game\CreateGameCommand;
use Deck\Domain\Game\Brisca;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameCreateController extends AbstractRenderController
{
    #[Route('/api/game', name: 'api.game.create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $tableId = $request->request->get('id');

        Assertion::notNull($tableId, 'Table Id can\'t be null');
        Assertion::string($tableId);

        $createGameCommand = new CreateGameCommand($tableId, new Brisca());
        $this->execute($createGameCommand);

        return $this->createApiResponse(['id' => $createGameCommand->gameId()->value()], Response::HTTP_CREATED);
    }
}
