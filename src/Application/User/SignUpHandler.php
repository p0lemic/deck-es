<?php

declare(strict_types=1);

namespace Deck\Application\User;

use Deck\Domain\User\Player;
use Deck\Domain\User\PlayerRepositoryInterface;
use Deck\Domain\User\Specification\UniqueEmailSpecificationInterface;

class SignUpHandler
{
    /** @var PlayerRepositoryInterface */
    private $playerRepository;
    /** @var UniqueEmailSpecificationInterface */
    private $uniqueEmailSpecification;

    public function __construct(
        PlayerRepositoryInterface $playerRepository,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification
    ) {
        $this->playerRepository = $playerRepository;
        $this->uniqueEmailSpecification = $uniqueEmailSpecification;
    }

    public function handle(SignUpCommand $command): void
    {
        $user = Player::create($command->credentials, $this->uniqueEmailSpecification);

        $this->playerRepository->save($user);
    }
}
