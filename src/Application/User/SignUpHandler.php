<?php

declare(strict_types=1);

namespace Deck\Application\User;

use Deck\Domain\User\Player;
use Deck\Domain\User\PlayerRepositoryInterface;
use Deck\Domain\User\Specification\UniqueEmailSpecificationInterface;
use Deck\Infrastructure\Events\EventBus;

class SignUpHandler
{
    /** @var PlayerRepositoryInterface */
    private $playerRepository;
    /** @var UniqueEmailSpecificationInterface */
    private $uniqueEmailSpecification;
    /** @var EventBus */
    private $eventBus;

    public function __construct(
        PlayerRepositoryInterface $playerRepository,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification,
        EventBus $eventBus
    ) {
        $this->playerRepository = $playerRepository;
        $this->uniqueEmailSpecification = $uniqueEmailSpecification;
        $this->eventBus = $eventBus;
    }

    public function handle(SignUpCommand $command): void
    {
        $user = Player::create($command->credentials(), $this->uniqueEmailSpecification);

        $this->playerRepository->save($user);

        $this->eventBus->publishEvents($user->getRecordedEvents());
    }
}
