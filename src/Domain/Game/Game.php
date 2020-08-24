<?php

namespace Deck\Domain\Game;

use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Deck\Domain\Game\Event\GameWasCreated;
use Deck\Domain\Game\Event\GameWasJoined;
use Deck\Domain\Game\Exception\CardsNumberInUseNotValidException;
use Deck\Domain\Game\Exception\InvalidPlayerNumber;
use Deck\Domain\Shared\ValueObject\DateTime;
use function count;

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
class Game extends EventSourcedAggregateRoot
{
    private GameId $id;
    private Deck $deck;
    /** @var Player[] */
    private array $players;

    public static function create(
        GameId $gameId,
        array $players
    ): self {
        $game = new self();
        $game->apply(new GameWasCreated($gameId, $players, DateTime::now()));

        return $game;
    }

    public function deck(): Deck
    {
        return $this->deck;
    }

    /** @return Player[] */
    public function players(): array
    {
        return $this->players;
    }

    public function join(Player $player): void
    {
        $this->apply(new GameWasJoined($this->id, $player, DateTime::now()));
    }

    public function initGame(): void
    {
        $this->deck->shuffleCards();
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
        foreach ($event->players() as $playerId) {
            $this->players[] = Player::create($playerId);
        }
        $this->deck = Deck::create(DeckId::create(), $this->id);
    }

    public function applyGameWasJoined(GameWasJoined $gameWasJoined): void
    {
        $totalPlayers = count($this->players());

        if ($totalPlayers >= 2) {
            throw InvalidPlayerNumber::gameIsFull();
        }

        $this->players[] = $gameWasJoined->player();
    }

    public function getAggregateRootId(): string
    {
        return $this->id->value();
    }

    protected function getChildEntities(): array
    {
        $children = [];

        foreach ($this->players() as $player) {
            $children[] = $player;
        }

        $children[] = $this->deck;

        return $children;
    }
}
