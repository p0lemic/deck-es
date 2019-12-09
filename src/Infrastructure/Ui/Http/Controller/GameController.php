<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Ui\Http\Controller;

use Deck\Application\Game\CreateGameCommand;
use Deck\Application\Game\LoadGame;
use Deck\Application\Game\LoadGameRequest;
use Deck\Domain\Game\GameId;
use Deck\Domain\User\Cpu;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use function var_dump;

class GameController extends AbstractRenderController
{
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
    public function game(Security $security): Response
    {
        $user = $security->getUser();

        $players = [
            $user ? $user->getUsername() : null
        ];
        $this->execute(new CreateGameCommand(GameId::create()->value(), $players));

        return $this->render('game/index.html.twig');
    }

    /**
     * @Route(
     *     "/game/play",
     *     name="game-play",
     *     methods={"GET"}
     * )
     *
     * @param Request $request
     * @param LoadGame $loadGame
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function playGame(Request $request, LoadGame $loadGame): Response
    {
        $id = $request->query->get('id');

        $game = $loadGame->execute(new LoadGameRequest($id));

        return $this->render(
            'game/deck.html.twig',
            [
                'deck' => $game->deck()
            ]
        );
    }
}
