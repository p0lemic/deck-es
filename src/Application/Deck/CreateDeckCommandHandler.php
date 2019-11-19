<?php

declare(strict_types=1);

namespace Deck\Application\Deck;

use Broadway\EventSourcing\EventSourcingRepository;
use Deck\Domain\Deck\Deck;

class CreateDeckCommandHandler
{
    private $repository;

    public function __construct(EventSourcingRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(CreateDeckCommand $command): void
    {
        $deck = Deck::create($command->deckId());

        $this->repository->save($deck);
    }
}
