<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Ui\Http\Controller;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Deck\Application\User\SignUpCommand;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends AbstractRenderController
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
     * @OA\Response(
     *     response=500,
     *     description="Internal Server Error"
     * )
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
     * @throws AssertionFailedException
     */
    public function register(Request $request): Response
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
