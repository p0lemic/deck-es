<?php

declare(strict_types=1);

namespace Deck\Infrastructure\User\Auth;

use Deck\Domain\User\PlayerReadModelRepositoryInterface;
use Deck\Domain\User\ValueObject\Email;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    /** @var PlayerReadModelRepositoryInterface */
    private $playerRepository;

    public function __construct(PlayerReadModelRepositoryInterface $playerRepository)
    {
        $this->playerRepository = $playerRepository;
    }

    public function loadUserByUsername($username): UserInterface
    {
        [$id, $email, $hashedPassword] = $this->playerRepository->getCredentialsByEmail(
            Email::fromString($username)
        );

        return Auth::create($id, $email, $hashedPassword);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof Auth) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class): bool
    {
        return Auth::class === $class;
    }
}
