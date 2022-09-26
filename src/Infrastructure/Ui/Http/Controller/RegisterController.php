<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Ui\Http\Controller;

use Assert\Assertion;
use Deck\Application\User\SignUpCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractRenderController
{
    #[Route('/api/user', name: 'api.user', methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        Assertion::notNull($email, 'Email can\'t be null');
        Assertion::notNull($password, 'Password can\'t be null');
        Assertion::string($email);
        Assertion::string($password);

        $signUpCommand = new SignUpCommand($email, $password);

        $this->execute($signUpCommand);

        return $this->createApiResponse(['id' => $signUpCommand->id()->value()], Response::HTTP_CREATED);
    }
}
