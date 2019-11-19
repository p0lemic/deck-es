<?php

declare(strict_types=1);

namespace Deck\Application\User;

use Deck\Domain\Aggregate\AggregateId;
use Deck\Domain\User\Exception\InvalidCredentialsException;
use Deck\Domain\User\Player;
use Deck\Domain\User\PlayerId;
use Deck\Domain\User\PlayerRepositoryInterface;
use Deck\Domain\User\ValueObject\Email;
use Deck\Infrastructure\Events\EventBus;

class SignInHandler
{
    /** @var PlayerRepositoryInterface */
    private $userStore;
    /** @var EventBus */
    private $eventBus;

    public function __construct(
        PlayerRepositoryInterface $userStore,
        EventBus $eventBus
    ) {
        $this->userStore = $userStore;
        $this->eventBus = $eventBus;
    }

    /**
     * @param SignInCommand $command
     * @return void
     * @throws InvalidCredentialsException
     */
    public function __invoke(SignInCommand $command): void
    {
        $aggregateId = $this->uuidFromEmail($command->email());

        /** @var Player $user */
        $user = $this->userStore->findByIdOrFail(PlayerId::fromString($aggregateId->value()->toString()));

        $user->signIn($command->plainPassword());

        $this->userStore->save($user);

        $this->eventBus->publishEvents($user->getRecordedEvents());
    }

    private function uuidFromEmail(Email $email): AggregateId
    {
        $aggregateId = $this->userStore->existsEmail($email);

        if (null === $aggregateId) {
            throw InvalidCredentialsException::invalid();
        }

        return $aggregateId;
    }
}
