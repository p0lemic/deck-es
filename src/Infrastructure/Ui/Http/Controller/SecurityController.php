<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Ui\Http\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractRenderController
{
    public function login(AuthenticationUtils $authUtils): Response
    {
        return $this->json(
            [
                'last_username' => $authUtils->getLastUsername(),
                'error' => $authUtils->getLastAuthenticationError(),
            ]
        );
    }

    public function logout(): void
    {
        throw new AuthenticationException('I shouldn\'t be here..');
    }
}
