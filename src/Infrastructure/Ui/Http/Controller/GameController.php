<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Ui\Http\Controller;

use Deck\Application\Game\CreateGameCommand;
use Deck\Domain\Game\GameId;
use Deck\Domain\User\Cpu;
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
     *     "/game",
     *     name="game",
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
        $this->execute(new CreateGameCommand(GameId::create()->value()->toString(), $players));

        return $this->render('game/index.html.twig');
    }
}
