<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Ui\Http\Controller;

use Deck\Application\Game\CreateGameCommand;
use Deck\Application\Game\ListGames;
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
    /**
     * @Route(
     *     "/game/list",
     *     name="game-list",
     *     methods={"GET"}
     * )
     *
     * @param ListGames $listGames
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function list(ListGames $listGames): Response
    {
        $games = $listGames->execute();

        return $this->render(
            'game/list.html.twig',
            [
                'games' => $games,
            ]
        );
    }

    /**
     * @Route(
     *     "/game/create",
     *     name="game-create",
     *     methods={"GET"}
     * )
     *
     * @param Security $security
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function create(Security $security): Response
    {
        $user = $security->getUser();

        $players = [
            $user ? $user->getUsername() : null,
        ];
        $this->execute(new CreateGameCommand(GameId::create()->value(), $players));

        return $this->render('game/index.html.twig');
    }

    /**
     * @Route(
     *     "/game/play/{id}",
     *     name="game-play",
     *     methods={"GET"}
     * )
     *
     * @param LoadGame $loadGame
     * @param string $id
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function play(
        LoadGame $loadGame,
        string $id
    ): Response {
        $game = $loadGame->execute(new LoadGameRequest($id));

        return $this->render(
            'game/deck.html.twig',
            [
                'deck' => $game->deck(),
            ]
        );
    }
}
