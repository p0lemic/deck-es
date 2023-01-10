<?php

namespace Deck\Application\Game;

use Deck\Domain\Game\Exception\CardsNumberInUseNotValidException;
use Deck\Domain\Game\Exception\PlayerNotAllowedToDraw;
use Deck\Domain\Game\GameRepositoryInterface;
use Deck\Domain\Shared\Exception\DateTimeException;
use RuntimeException;

class DrawCardHandler
{
    private GameRepositoryInterface $gameStore;

    public function __construct(
        GameRepositoryInterface $gameStore
    ) {
        $this->gameStore = $gameStore;
    }

    /**
     * @throws PlayerNotAllowedToDraw
     * @throws DateTimeException
     * @throws CardsNumberInUseNotValidException
     */
    public function handle(DrawCardCommand $drawCardCommand): void
    {
        $game = $this->gameStore->get($drawCardCommand->gameId());
        $player = $game->getPlayer($drawCardCommand->playerId());

        $game->playerDraw($player);

        $this->gameStore->store($game);
    }
}
