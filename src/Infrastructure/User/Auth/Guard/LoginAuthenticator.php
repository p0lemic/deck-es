<?php

declare(strict_types=1);

namespace Deck\Infrastructure\User\Auth\Guard;

use Deck\Application\User\SignInCommand;
use Deck\Domain\User\Exception\InvalidCredentialsException;
use Deck\Domain\User\Player;
use Deck\Domain\User\PlayerReadModelRepositoryInterface;
use Deck\Domain\User\ValueObject\Email;
use Deck\Infrastructure\User\Auth\Auth;
use SimpleBus\SymfonyBridge\Bus\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

final class LoginAuthenticator extends AbstractFormLoginAuthenticator
{
    private const LOGIN = 'api.user.login';

    private CommandBus $bus;
    private UrlGeneratorInterface $router;
    private PlayerReadModelRepositoryInterface $playerRepository;

    public function __construct(
        CommandBus $commandBus,
        UrlGeneratorInterface $router,
        PlayerReadModelRepositoryInterface $playerRepository
    ) {
        $this->bus = $commandBus;
        $this->router = $router;
        $this->playerRepository = $playerRepository;
    }

    protected function getLoginUrl(): string
    {
        return $this->router->generate(self::LOGIN);
    }

    public function supports(Request $request): bool
    {
        return $request->getPathInfo() === $this->router->generate(self::LOGIN) && $request->isMethod('POST');
    }

    /**
     * Get the authentication credentials from the request and return them
     * as any type (e.g. an associate array).
     *
     * Whatever value you return here will be passed to getUser() and checkCredentials()
     *
     * For example, for a form login, you might:
     *
     *      return [
     *          'username' => $request->request->get('_username'),
     *          'password' => $request->request->get('_password'),
     *      ];
     *
     * Or for an API token that's on a header, you might use:
     *
     *      return ['api_key' => $request->headers->get('X-API-TOKEN')];
     *
     * @param Request $request
     * @return array
     */
    public function getCredentials(Request $request): array
    {
        return [
            'email' => $request->request->get('_email'),
            'password' => $request->request->get('_password'),
        ];
    }

    /**
     * Return a UserInterface object based on the credentials.
     *
     * The *credentials* are the return value from getCredentials()
     *
     * You may throw an AuthenticationException if you wish. If you return
     * null, then a UsernameNotFoundException is thrown for you.
     *
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     *
     * @return UserInterface|null
     * @throws AuthenticationException
     *
     */
    public function getUser(
        $credentials,
        UserProviderInterface $userProvider
    ): ?UserInterface {
        try {
            $email = $credentials['email'];
            $plainPassword = $credentials['password'];

            $query = new SignInCommand($email, $plainPassword);

            $this->bus->handle($query);

            /** @var Player $user */
            $user = $this->playerRepository->findByEmailOrFail(Email::fromString($email));

            return Auth::create(
                $user->id(),
                $user->email(),
                $user->hashedPassword()
            );
        } catch (InvalidCredentialsException $exception) {
            throw new AuthenticationException($exception->getMessage());
        }
    }

    /**
     * Returns true if the credentials are valid.
     *
     * If any value other than true is returned, authentication will
     * fail. You may also throw an AuthenticationException if you wish
     * to cause authentication to fail.
     *
     * The *credentials* are the return value from getCredentials()
     *
     * @param mixed $credentials
     * @param UserInterface $user
     *
     * @return bool
     *
     * @throws AuthenticationException
     */
    public function checkCredentials(
        $credentials,
        UserInterface $user
    ) {
        return true;
    }

    /**
     * Called when authentication executed and was successful!
     *
     * This should return the Response sent back to the user, like a
     * RedirectResponse to the last page they visited.
     *
     * If you return null, the current request will continue, and the user
     * will be authenticated. This makes sense, for example, with an API.
     *
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey The provider (i.e. firewall) key
     *
     * @return Response|null
     */
    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
        $providerKey
    ): ?Response {
        return new JsonResponse(true);
    }
}
