<?php

namespace Deck\Application\Game;

use Deck\Domain\Game\Exception\InvalidPlayerNumberException;
use Deck\Domain\Game\GameFactory;
use Deck\Domain\Game\GameRepositoryInterface;
use Deck\Domain\Table\TableReadModelRepositoryInterface;
use Deck\Domain\User\PlayerId;
use Deck\Domain\User\PlayerReadModelRepositoryInterface;

class CreateGameHandler
{
    private GameFactory $gameFactory;
    private GameRepositoryInterface $gameStore;
    private PlayerReadModelRepositoryInterface $playerRepository;
    private TableReadModelRepositoryInterface $tableRepository;

    public function __construct(
        GameFactory $gameFactory,
        GameRepositoryInterface $gameStore,
        PlayerReadModelRepositoryInterface $playerRepository,
        TableReadModelRepositoryInterface $tableRepository
    ) {
        $this->gameFactory = $gameFactory;
        $this->gameStore = $gameStore;
        $this->playerRepository = $playerRepository;
        $this->tableRepository = $tableRepository;
    }

    public function handle(CreateGameCommand $createGameCommand): void
    {
        $table = $this->tableRepository->findByTableId($createGameCommand->tableId());

        if (!$table->isFull()) {
            throw InvalidPlayerNumberException::gameTableIsNotFull();
        }

        $players = [];

        foreach ($table->players() as $playerId) {
            $player = $this->playerRepository->findById(PlayerId::fromString($playerId));

            $players[] = $player->id();
        }

        $game = $this->gameFactory->createNewGame($createGameCommand->gameId(), $createGameCommand->deckId(), $players, $createGameCommand->rules());
        $game->initGame();

        $this->gameStore->store($game);
    }
}
