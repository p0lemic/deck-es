<?php

namespace Deck\Domain\Game;

use Deck\Domain\Aggregate\Aggregate;
use Deck\Domain\Deck\Deck;
use Deck\Domain\Game\Event\GameWasCreated;
use Deck\Domain\Game\Exception\CardsNumberInUseNotValidException;
use Deck\Domain\User\Player;

/**
 * Aggregate Root
 *
 * Contains entities of Deck and Player. The invariants are constraints on the state of the
 * aggregate, that should hold at any point in time (transactional consistency).
 *
 * The state of the aggregate is the superposition of the states of its entities Players and the value of
 * its Value Objects. That's why it can't pass the entities' references away, otherwise their state could be changed
 * outside of the aggregate, breaking the invariants.
 *
 * Invariant 1. The deck, and the players all together must have 52 unique cards
 *
 */
class Game extends Aggregate
{
    /** @var Deck */
    private $deck;
    /** @var Player[] */
    private $players;

    public function __construct(
        Deck $deck,
        array $players
    ) {
        $gameWasCreatedEvent = new GameWasCreated(GameId::create(), $deck, $players);

        $this->recordThat($gameWasCreatedEvent);
        $this->applyGameWasCreated($gameWasCreatedEvent);
    }

    public function deck(): Deck
    {
        return $this->deck;
    }

    /**
     * @return Player[]
     */
    public function players(): array
    {
        return $this->players;
    }

    /**
     * @param Player $player
     * @return void
     * @throws CardsNumberInUseNotValidException
     */
    public function playerDraw(Player $player): void
    {
        $this->assertTotalCardsInGameAreConsistency();

        $card = $this->deck->draw();
        $player->addCardToPlayersHand($card);
    }

    /**
     * @return void
     * @throws CardsNumberInUseNotValidException
     */
    public function assertTotalCardsInGameAreConsistency(): void
    {
        $totalCardsInDeck = count($this->deck->cards());

        $totalCardsInPlayersHand = 0;
        foreach ($this->players() as $player) {
            $totalCardsInPlayersHand += count($player->hand());
        }

        if (Deck::TOTAL_INITIAL_CARDS_IN_DECK !== ($totalCardsInDeck + $totalCardsInPlayersHand)) {
            throw CardsNumberInUseNotValidException::invalidNumber(
                Deck::TOTAL_INITIAL_CARDS_IN_DECK,
                $totalCardsInDeck + $totalCardsInPlayersHand
            );
        }
    }

    public function applyGameWasCreated(GameWasCreated $event): void
    {
        $this->id = $event->aggregateId();
        $this->deck = $event->deck();
        $this->players = $event->players();
    }
}
