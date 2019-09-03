<?php

namespace Deck\Infrastructure\Ui\Http\Controller\Game;

use Deck\Application\Game\CreateGameCommand;
use Deck\Domain\Game\GameId;
use Deck\Domain\Game\GameRepositoryInterface;
use Deck\Infrastructure\Events\MessageBus;
use SimpleBus\SymfonyBridge\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    /** @var CommandBus */
    private $commandBus;
    /** @var GameRepositoryInterface */
    private $gameRepository;

    public function __construct(
        MessageBus $commandBus,
        GameRepositoryInterface $gameRepository
    ) {
        $this->commandBus = $commandBus;
        $this->gameRepository = $gameRepository;
    }

    public function index(Request $request): Response
    {
        //try {
        $gameId = GameId::create();
        $this->commandBus->handle(
            new CreateGameCommand($gameId->value()->toString(), ['Player 1', 'CPU'])
        );

        $game = $this->gameRepository->findByGameId($gameId->value()->toString());
        return $this->render(
            '@DeckTwigTemplates/game/deck.html.twig',
            [
                'game' => $game,
            ]
        );
        /*
    } catch (Throwable $e) {
        return $this->render(
            '@DeckTwigTemplates/error/index.html.twig',
            [
                'error' => $e->getMessage(),
            ]
        );
    }
        */
    }
}
