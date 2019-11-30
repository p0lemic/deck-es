<?php

namespace Deck\Application\Game;

use Deck\Domain\Game\GameFactory;
use Deck\Domain\Game\GameRepositoryInterface;
use Deck\Domain\Game\Player;
use Deck\Domain\User\PlayerReadModelRepositoryInterface;
use Deck\Domain\User\ValueObject\Email;

class CreateGameHandler
{
    /** @var GameFactory */
    private $gameFactory;
    /** @var GameRepositoryInterface */
    private $gameStore;
    /** @var PlayerReadModelRepositoryInterface */
    private $playerRepository;

    public function __construct(
        GameFactory $gameFactory,
        GameRepositoryInterface $gameStore,
        PlayerReadModelRepositoryInterface $playerRepository
    ) {
        $this->gameFactory = $gameFactory;
        $this->gameStore = $gameStore;
        $this->playerRepository = $playerRepository;
    }

    public function handle(CreateGameCommand $createGameRequest): void
    {
        $players = [];
        foreach ($createGameRequest->players() as $playerId) {
            $player = $this->playerRepository->findByEmailOrFail(Email::fromString($playerId));

            $players[] = Player::create($player->id());
        }
        $game = $this->gameFactory->createNewGame($players);

        $this->gameStore->store($game);
    }
}
