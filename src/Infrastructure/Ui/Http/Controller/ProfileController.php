<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Ui\Http\Controller;

use Deck\Application\Deck\CreateDeckCommand;
use Deck\Domain\Deck\DeckId;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ProfileController extends AbstractRenderController
{
    /**
     * @Route(
     *     "/profile",
     *     name="profile",
     *     methods={"GET"}
     * )
     *
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function profile(): Response
    {
        return $this->render('profile/index.html.twig');
    }
}
