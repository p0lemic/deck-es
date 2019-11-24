<?php

namespace Deck\Application\Game;

use Deck\Domain\Game\GameFactory;
use Deck\Domain\Game\GameRepositoryInterface;
use Deck\Domain\User\PlayerReadModelRepositoryInterface;
use Deck\Domain\User\ValueObject\Email;
use Deck\Infrastructure\Events\EventBus;

class CreateGameHandler
{
    /** @var GameFactory */
    private $gameFactory;
    /** @var GameRepositoryInterface */
    private $gameRepository;
    /** @var EventBus */
    private $eventBus;
    /** @var PlayerReadModelRepositoryInterface */
    private $playerRepository;

    public function __construct(
        GameFactory $gameFactory,
        GameRepositoryInterface $gameRepository,
        PlayerReadModelRepositoryInterface $playerRepository,
        EventBus $eventBus
    ) {
        $this->gameFactory = $gameFactory;
        $this->gameRepository = $gameRepository;
        $this->eventBus = $eventBus;
        $this->playerRepository = $playerRepository;
    }

    public function handle(CreateGameCommand $createGameRequest): void
    {
        $players = [];
        foreach ($createGameRequest->players() as $playerId) {
            $players[] = $this->playerRepository->findByEmailOrFail(Email::fromString($playerId));
        }
        $game = $this->gameFactory->createNewGame($players);

        $this->gameRepository->save($game);

        $this->eventBus->publishEvents($game->getRecordedEvents());
        $this->eventBus->publishEvents($game->deck()->getUncommittedEvents());
    }
}
