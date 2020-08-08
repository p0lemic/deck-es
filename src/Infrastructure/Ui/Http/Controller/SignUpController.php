<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Ui\Http\Controller;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Deck\Application\User\SignUpCommand;
use Deck\Domain\User\Exception\EmailAlreadyExistException;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SignUpController extends AbstractRenderController
{
    public function signUp(Request $request): Response
    {
        $email = $request->get('email');
        $password = $request->get('password');

        try {
            Assertion::notNull($email, 'Email can\'t be null');
            Assertion::notNull($password, 'Password can\'t be null');

            $signUpCommand = new SignUpCommand($email, $password);

            $this->execute($signUpCommand);

            return $this->createApiResponse(['id' => $signUpCommand->id()]);
        } catch (EmailAlreadyExistException $exception) {
            return $this->createApiResponse(['error' => 'Email already exists.'], Response::HTTP_CONFLICT);
        } catch (InvalidArgumentException|AssertionFailedException $exception) {
            return $this->createApiResponse(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
