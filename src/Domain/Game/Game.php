<?php

namespace Deck\Domain\Game;

use Deck\Domain\Deck\Deck;
use Deck\Domain\Deck\DeckFactory;
use Deck\Domain\Game\Exception\CardsNumberInUseNotValidException;
use Deck\Domain\User\Player;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

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

class Game
{
    /** @var UuidInterface */
    private $id;

    /** @var Deck */
    private $deck;

    /** @var Player[] */
    private $players;

    public function __construct(DeckFactory $deckFactory, array $players)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->deck = $deckFactory->createNew();
        $this->players = $players;
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function deck(): Deck
    {
        return $this->deck;
    }

    /**
     * @param Player $player
     * @return void
     * @throws CardsNumberInUseNotValidException
     */
    public function playerDraw(Player $player): void
    {
        $card = $this->deck->draw();

        $player->addCardToPlayersHand($card);

        $this->assertTotalCardsInGameAreConsistency();
    }

    /**
     * @return Player[]
     */
    public function players(): array
    {
        return $this->players;
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
}
