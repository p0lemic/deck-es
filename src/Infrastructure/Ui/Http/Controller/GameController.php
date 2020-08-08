<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Ui\Http\Controller;

use Deck\Application\Game\CreateGameCommand;
use Deck\Application\Game\GamesListQuery;
use Deck\Application\Game\LoadGame;
use Deck\Application\Game\LoadGameRequest;
use Deck\Domain\Game\GameId;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GameController extends AbstractRenderController
{
    public function list(GamesListQuery $listGames): Response
    {
        $games = $listGames->execute();

        return $this->createApiResponse($games);
    }

    public function create(Security $security): Response
    {
        $user = $security->getUser();

        $players = [
            $user ? $user->getUsername() : null,
        ];
        $this->execute(new CreateGameCommand(GameId::create()->value(), $players));

        return $this->createApiResponse([], Response::HTTP_CREATED);
    }

    public function play(
        LoadGame $loadGame,
        string $id
    ): Response {
        $game = $loadGame->execute(new LoadGameRequest($id));

        return $this->createApiResponse($game->getAggregateRootId());
    }
}
