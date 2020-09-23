<?php

namespace Deck\Application\Game;

use Deck\Domain\Game\GameRepositoryInterface;

class DrawCardHandler
{
    private GameRepositoryInterface $gameStore;

    public function __construct(
        GameRepositoryInterface $gameStore
    ) {
        $this->gameStore = $gameStore;
    }

    public function handle(DrawCardCommand $drawCardCommand): void
    {
        $game = $this->gameStore->get($drawCardCommand->gameId());
        $player = $game->getPlayer($drawCardCommand->playerId());

        if (null === $player) {
            throw new \RuntimeException('Player not found');
        }

        $game->playerDraw($player);

        $this->gameStore->store($game);
    }
}
