<?php

namespace Deck\Infrastructure\Ui\Http\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends AbstractController
{
    public function signIn(Request $request): Response
    {
        return $this->render(
            '@DeckTwigTemplates/home/index.html.twig'
        );
    }

    public function signUp(Request $request): Response
    {
        return $this->render(
            '@DeckTwigTemplates/home/index.html.twig'
        );
    }

    public function logout(Request $request): Response
    {
        return $this->render(
            '@DeckTwigTemplates/home/index.html.twig'
        );
    }
}
