<?php

namespace Deck\Infrastructure\Ui\Http\Controller\Game;

use Deck\Application\Game\CreateGameCommand;
use Deck\Application\Game\CreateGameHandler;
use function print_r;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class HomeController extends AbstractController
{
    /** @var CreateGameHandler */
    private $createDeckService;

    public function __construct(CreateGameHandler $createDeckService)
    {
        $this->createDeckService = $createDeckService;
    }

    public function index(Request $request): Response
    {
        //try {
            $createGameRequest = new CreateGameCommand(
                [
                    'Player 1',
                    'Player 2'
                ]
            );

            $game = $this->createDeckService->handle($createGameRequest);

            return $this->render(
                '@DeckTwigTemplates/game/deck.html.twig',
                [
                    'game' => $game
                ]
            );
        //} catch (Throwable $e) {
            //$this->addFlash('error', $e->getMessage());

            //return $this->redirect($request->getUri());
        //}
    }
}
