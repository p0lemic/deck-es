<?php

declare(strict_types=1);

namespace Deck\Application\User;

use Deck\Domain\User\Player;
use Deck\Domain\User\PlayerRepositoryInterface;
use Deck\Domain\User\Specification\UniqueEmailSpecificationInterface;

class SignUpHandler
{
    private PlayerRepositoryInterface $userStore;
    private UniqueEmailSpecificationInterface $uniqueEmailSpecification;

    public function __construct(
        PlayerRepositoryInterface $userStore,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification
    ) {
        $this->userStore = $userStore;
        $this->uniqueEmailSpecification = $uniqueEmailSpecification;
    }

    public function handle(SignUpCommand $command): void
    {
        $user = Player::create($command->id(), $command->credentials(), $this->uniqueEmailSpecification);

        $this->userStore->store($user);
    }
}
