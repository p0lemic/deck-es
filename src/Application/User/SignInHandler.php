<?php

declare(strict_types=1);

namespace Deck\Application\User;

use Deck\Domain\Aggregate\AggregateId;
use Deck\Domain\Shared\Exception\DateTimeException;
use Deck\Domain\User\PlayerReadModelRepositoryInterface;
use Deck\Domain\User\Exception\InvalidCredentialsException;
use Deck\Domain\User\Player;
use Deck\Domain\User\PlayerId;
use Deck\Domain\User\PlayerRepositoryInterface;
use Deck\Domain\User\ValueObject\Email;

class SignInHandler
{
    /** @var PlayerRepositoryInterface */
    private $userStore;
    /** @var PlayerReadModelRepositoryInterface */
    private $playerRepository;

    public function __construct(
        PlayerRepositoryInterface $userStore,
        PlayerReadModelRepositoryInterface $playerRepository
    ) {
        $this->userStore = $userStore;
        $this->playerRepository = $playerRepository;
    }

    /**
     * @param SignInCommand $command
     * @return void
     * @throws InvalidCredentialsException
     * @throws DateTimeException
     */
    public function __invoke(SignInCommand $command): void
    {
        $aggregateId = $this->uuidFromEmail($command->email());

        /** @var Player $user */
        $user = $this->userStore->get(PlayerId::fromString($aggregateId->value()->toString()));

        $user->signIn($command->plainPassword());

        $this->userStore->store($user);
    }

    private function uuidFromEmail(Email $email): AggregateId
    {
        $aggregateId = $this->playerRepository->existsEmail($email);

        if (null === $aggregateId) {
            throw InvalidCredentialsException::invalid();
        }

        return $aggregateId;
    }
}
