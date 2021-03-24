<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Ui\Http\Controller;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Deck\Application\User\SignUpCommand;
use Deck\Domain\User\Exception\EmailAlreadyExistException;
use InvalidArgumentException;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SignUpController extends AbstractRenderController
{
    /**
     * Create a new user
     *
     * @OA\Response(
     *     response=201,
     *     description="User created successfully"
     * )
     * @OA\Response(
     *     response=400,
     *     description="Bad request"
     * )
     * @OA\Response(
     *     response=409,
     *     description="Conflict"
     * )
     *
     * @OA\RequestBody(
     *     request="user",
     *     required=true,
     *     @OA\JsonContent(ref="#/components/schemas/User"),
     * )
     *
     * @OA\Tag(name="User")
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

            return $this->createApiResponse(['id' => $signUpCommand->id()->value()], Response::HTTP_CREATED);
        } catch (EmailAlreadyExistException $exception) {
            return $this->createApiResponse(['error' => 'Email already exists.'], Response::HTTP_CONFLICT);
        } catch (InvalidArgumentException|AssertionFailedException $exception) {
            return $this->createApiResponse(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
