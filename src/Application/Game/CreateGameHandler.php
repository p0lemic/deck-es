<?php

namespace Deck\Application\Game;

use Deck\Domain\Game\GameFactory;
use Deck\Domain\Game\GameRepositoryInterface;
use Deck\Infrastructure\Events\EventBus;

class CreateGameHandler
{
    /** @var GameFactory */
    private $gameFactory;
    /** @var GameRepositoryInterface */
    private $gameRepository;
    /** @var EventBus */
    private $eventBus;

    public function __construct(
        GameFactory $gameFactory,
        GameRepositoryInterface $gameRepository,
        EventBus $eventBus
    ) {
        $this->gameFactory = $gameFactory;
        $this->gameRepository = $gameRepository;
        $this->eventBus = $eventBus;
    }

    public function handle(CreateGameCommand $createGameRequest): void
    {
        $game = $this->gameFactory->createNewGame($createGameRequest->players());

        $this->gameRepository->save($game);

        $this->eventBus->publishEvents($game->getRecordedEvents());
    }
}
