<?php

declare(strict_types=1);

namespace Deck\Infrastructure\User\Auth;

use Assert\AssertionFailedException;
use Deck\Domain\User\PlayerReadModelRepositoryInterface;
use Deck\Domain\User\ValueObject\Email;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    private PlayerReadModelRepositoryInterface $playerRepository;

    public function __construct(PlayerReadModelRepositoryInterface $playerRepository)
    {
        $this->playerRepository = $playerRepository;
    }

    /** @throws AssertionFailedException */
    public function loadUserByUsername(string $username): UserInterface
    {
        return $this->getUserData($username);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof Auth) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass(string $class): bool
    {
        return Auth::class === $class;
    }

    /** @throws AssertionFailedException */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->getUserData($identifier);
    }

    /**
     * @param string $identifier
     * @return Auth
     * @throws AssertionFailedException
     */
    private function getUserData(string $identifier): Auth
    {
        [$id, $email, $hashedPassword] = $this->playerRepository->getCredentialsByEmail(
            Email::fromString($identifier)
        );

        return Auth::create($id, $email, $hashedPassword);
    }
}
