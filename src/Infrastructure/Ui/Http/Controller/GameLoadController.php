<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Ui\Http\Controller;

use Assert\Assertion;
use Deck\Application\Game\LoadGameQuery;
use Deck\Domain\Game\GameId;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameLoadController extends AbstractRenderController
{
    #[Route('/api/game/{id}', name: 'api.game.load', methods: ['GET'])]
    public function load(
        LoadGameQuery $loadGame,
        Request $request,
        string $id
    ): Response {
        Assertion::notNull($id, 'Game Id can\'t be null');
        Assertion::string($id);

        $game = $loadGame->execute(GameId::fromString($id));

        return $this->createApiResponse($game->normalize());
    }
}
