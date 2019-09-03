<?php

namespace Deck\Application\Game;

use Deck\Domain\Game\GameFactory;
use Deck\Domain\Game\GameRepositoryInterface;
use function var_dump;

class CreateGameHandler
{
    /** @var GameFactory */
    private $gameFactory;
    /** @var GameRepositoryInterface */
    private $gameRepository;

    public function __construct(
        GameFactory $gameFactory,
        GameRepositoryInterface $gameRepository
    ) {
        $this->gameFactory = $gameFactory;
        $this->gameRepository = $gameRepository;
    }

    public function handle(CreateGameCommand $createGameRequest): void
    {
        $game = $this->gameFactory->createNewGame($createGameRequest->players());

        $this->gameRepository->save($game);
    }
}
