<?php

namespace Deck\Infrastructure\Ui\Http\Controller\Game;

use Deck\Application\Game\CreateGameRequest;
use Deck\Application\Game\CreateGameService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class HomeController extends AbstractController
{
    /** @var CreateGameService */
    private $createDeckService;

    public function __construct(CreateGameService $createDeckService)
    {
        $this->createDeckService = $createDeckService;
    }

    public function index(Request $request): Response
    {
        //try {
            $createGameRequest = new CreateGameRequest(
                [
                    'Player 1',
                    'Player 2'
                ]
            );

            $game = $this->createDeckService->execute($createGameRequest);

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
