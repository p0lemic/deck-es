<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Ui\Http\Controller;

use Deck\Application\Game\CreateGameCommand;
use Deck\Application\Game\GamesListQuery;
use Deck\Application\Game\LoadGame;
use Deck\Application\Game\LoadGameRequest;
use Deck\Application\Table\GetTablesQuery;
use Deck\Domain\Game\GameId;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GameController extends AbstractRenderController
{
    /**
     * List available tables
     *
     * @SWG\Response(
     *     response=200,
     *     description="Available games"
     * )
     *
     * @SWG\Tag(name="Game")
     *
     * @param GamesListQuery $listGames
     * @return Response
     */
    public function list(GamesListQuery $listGames): Response
    {
        $games = $listGames->execute();

        return $this->createApiResponse($games);
    }

    /**
     * Create new game
     *
     * @SWG\Response(
     *     response=201,
     *     description="Game created successfully"
     * )
     *
     * @SWG\Tag(name="Game")
     *
     * @param Security $security
     * @return Response
     */
    public function create(Security $security): Response
    {
        $user = $security->getUser();

        $players = [
            $user ? $user->getUsername() : null,
        ];
        $this->execute(new CreateGameCommand(GameId::create()->value(), $players));

        return $this->createApiResponse([], Response::HTTP_CREATED);
    }

    /**
     * Load a game
     *
     * @SWG\Response(
     *     response=200,
     *     description="Game loaded successfully"
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Bad request"
     * )
     * @SWG\Parameter(
     *     name="game",
     *     type="object",
     *     in="body",
     *     schema=@SWG\Schema(type="object",
     *         @SWG\Property(property="id", type="string")
     *     )
     * )
     *
     * @SWG\Tag(name="Game")
     *
     * @param LoadGame $loadGame
     * @param string $id
     * @return Response
     */
    public function load(
        LoadGame $loadGame,
        string $id
    ): Response {
        $game = $loadGame->execute(new LoadGameRequest($id));

        return $this->createApiResponse($game->getAggregateRootId());
    }
}
