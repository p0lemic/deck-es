<?php

namespace Deck\Infrastructure\Ui\Http\Controller;

use Deck\Infrastructure\Events\MessageBus;
use SimpleBus\SymfonyBridge\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    /** @var CommandBus */
    private $commandBus;

    public function __construct(
        MessageBus $commandBus
    ) {
        $this->commandBus = $commandBus;
    }

    public function index(Request $request): Response
    {
        return $this->render(
            '@DeckTwigTemplates/home/index.html.twig'
        );
    }
}
