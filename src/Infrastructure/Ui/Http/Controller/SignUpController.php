<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Ui\Http\Controller;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Deck\Application\User\SignUpCommand;
use Deck\Domain\User\Exception\EmailAlreadyExistException;
use InvalidArgumentException;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SignUpController extends AbstractRenderController
{
    /**
     * Create a new user
     *
     * @SWG\Response(
     *     response=201,
     *     description="User created successfully"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Bad request"
     * )
     * @SWG\Response(
     *     response=409,
     *     description="Conflict"
     * )
     * @SWG\Parameter(
     *     name="user",
     *     type="object",
     *     in="body",
     *     schema=@SWG\Schema(type="object",
     *         @SWG\Property(property="email", type="string"),
     *         @SWG\Property(property="password", type="string")
     *     )
     * )
     *
     * @SWG\Tag(name="User")
     *
     * @param Request $request
     * @return Response
     */
    public function signUp(Request $request): Response
    {
        $email = $request->get('email');
        $password = $request->get('password');

        try {
            Assertion::notNull($email, 'Email can\'t be null');
            Assertion::notNull($password, 'Password can\'t be null');

            $signUpCommand = new SignUpCommand($email, $password);

            $this->execute($signUpCommand);

            return $this->createApiResponse(['id' => $signUpCommand->id()], Response::HTTP_CREATED);
        } catch (EmailAlreadyExistException $exception) {
            return $this->createApiResponse(['error' => 'Email already exists.'], Response::HTTP_CONFLICT);
        } catch (InvalidArgumentException|AssertionFailedException $exception) {
            return $this->createApiResponse(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
