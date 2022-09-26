<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Ui\Http\Controller;

use Assert\Assertion;
use Deck\Application\Game\LoadGame;
use Deck\Application\Game\LoadGameRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameLoadController extends AbstractRenderController
{
    #[Route('/api/game/{id}', name: 'api.game.load', methods: ['GET'])]
    public function load(
        LoadGame $loadGame,
        Request $request,
        string $id
    ): Response {
        Assertion::notNull($id, 'Game Id can\'t be null');
        Assertion::string($id);

        $game = $loadGame->execute(new LoadGameRequest($id));

        return $this->createApiResponse($game->toArray());
    }
}
