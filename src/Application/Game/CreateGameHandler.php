<?php

namespace Deck\Application\Game;

use Deck\Domain\Game\Deck;
use Deck\Domain\Game\DeckId;
use Deck\Domain\Game\Exception\InvalidPlayerNumber;
use Deck\Domain\Game\GameFactory;
use Deck\Domain\Game\GameRepositoryInterface;
use Deck\Domain\Table\TableId;
use Deck\Domain\Table\TableReadModelRepositoryInterface;
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
        $table = $this->tableRepository->findByTableIdOrFail(TableId::fromString($createGameCommand->tableId()->value()));

        if (!$table->isFull()) {
            throw InvalidPlayerNumber::gameTableIsNotFull();
        }

        $players = [];
        foreach ($table->players() as $playerId) {
            $player = $this->playerRepository->findByIdOrFail($playerId);

            $players[] = $player->id();
        }
        $game = $this->gameFactory->createNewGame($createGameCommand->gameId(), $createGameCommand->deckId(), $players, $createGameCommand->rules());
        $game->initGame();

        $this->gameStore->store($game);
    }
}
