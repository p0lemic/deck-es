<?php

namespace Deck\Domain\Game;

use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Deck\Domain\Game\Event\CardWasAddedToDeck;
use Deck\Domain\Game\Event\CardWasDealt;
use Deck\Domain\Game\Event\CardWasDrawn;
use Deck\Domain\Game\Event\CardWasPlayed;
use Deck\Domain\Game\Event\GameWasCreated;
use Deck\Domain\Game\Event\HandWasWon;
use Deck\Domain\Game\Exception\CardsNumberInUseNotValidException;
use Deck\Domain\Game\Exception\DeckCardsNumberException;
use Deck\Domain\Game\Exception\PlayerNotAllowedToDraw;
use Deck\Domain\Shared\Exception\DateTimeException;
use Deck\Domain\Shared\ValueObject\DateTime;
use Deck\Domain\User\PlayerId;
use function count;
use function next;
use function reset;

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
    public const TOTAL_INITIAL_CARDS_IN_DECK = 40;

    /** @psalm-suppress PropertyNotSetInConstructor */
    private GameId $id;
    /** @psalm-suppress PropertyNotSetInConstructor */
    private Deck $deck;
    /**
     * @var Player[]
     * @psalm-suppress PropertyNotSetInConstructor
     */
    private array $players;
    /** @psalm-suppress PropertyNotSetInConstructor */
    private ?PlayerId $currentPlayerId;
    /**
     * @var Card[]
     * @psalm-suppress PropertyNotSetInConstructor
     */
    private array $cardsOnTable;
    /** @psalm-suppress PropertyNotSetInConstructor */
    private Rules $rules;

    public function __construct()
    {
    }

    public static function create(
        GameId $gameId,
        DeckId $deckId,
        array $players,
        Rules $rules,
    ): self {
        $game = new self();
        $game->apply(new GameWasCreated($gameId, $players, $deckId, $rules, DateTime::now()));

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

    public function getPlayer(PlayerId $playerId): ?Player
    {
        foreach ($this->players as $player) {
            if ($player->playerId()->equals($playerId)) {
                return $player;
            }
        }

        return null;
    }

    public function currentPlayerId(): ?PlayerId
    {
        return $this->currentPlayerId;
    }

    public function initGame(): void
    {
        $this->shuffleCards();

        foreach ($this->players() as $player) {
            $this->dealInitialHand($player);
        }

        $this->rules->setSampleCard($this->deck->getLastCard());
    }

    public function shuffleCards(): void
    {
        $cards = [];

        foreach (Suite::AVAILABLE_SUITES as $suite) {
            foreach (Rank::AVAILABLE_RANKS as $rank) {
                $cards[] = new Card(new Suite($suite), new Rank($rank));
            }
        }

        if (count($cards) !== self::TOTAL_INITIAL_CARDS_IN_DECK) {
            throw DeckCardsNumberException::invalidInitialNumber(self::TOTAL_INITIAL_CARDS_IN_DECK, count($cards));
        }

        shuffle($cards);

        foreach ($cards as $card) {
            $this->apply(new CardWasAddedToDeck($this->id, $card, DateTime::now()));
        }
    }

    private function dealInitialHand(Player $player): void
    {
        for ($i = 0; $i < $this->rules::MAX_CARDS_IN_PLAYER_HAND; $i++) {
            $this->playerDraw($player);
        }
    }

    /**
     * @param Player $player
     * @return void
     * @throws CardsNumberInUseNotValidException|DateTimeException
     * @throws PlayerNotAllowedToDraw
     */
    public function playerDraw(Player $player): void
    {
        if ($this->rules::MAX_CARDS_IN_PLAYER_HAND <= count($player->hand())) {
            throw PlayerNotAllowedToDraw::isFull();
        }

        $this->assertTotalCardsInGameAreConsistency();

        $card = $this->deck->draw();

        $this->apply(new CardWasDrawn($this->id, $card, DateTime::now()));

        $this->apply(new CardWasDealt($this->id, $player->playerId(), $card, DateTime::now()));
    }

    public function playCard(
        Player $player,
        Card $card
    ): void {
        $this->apply(new CardWasPlayed($this->id, $player->playerId(), $card, DateTime::now()));

        if ($this->areAllCardsPlayed()) {
            $this->resolveTurn();
        }
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

    private function getNextPlayer(): ?PlayerId
    {
        foreach ($this->players as $playerId => $player) {
            if ($this->currentPlayerId?->value() === $playerId) {
                $nextPlayer = next($this->players);
                return $nextPlayer ? $nextPlayer->playerId() : reset($this->players)->playerId();
            }
        }

        return null;
    }

    private function areAllCardsPlayed(): bool
    {
        return count($this->cardsOnTable) === count($this->players);
    }

    private function resolveTurn(): void
    {
        $playerId = $this->rules->resolveHand($this->cardsOnTable);

        $this->apply(new HandWasWon($this->id, $playerId, $this->cardsOnTable, DateTime::now()));
    }

    public function applyGameWasCreated(GameWasCreated $event): void
    {
        $this->id = $event->aggregateId();
        foreach ($event->players() as $playerId) {
            $this->players[] = Player::create($playerId);
            $this->currentPlayerId = $playerId;
        }
        $this->cardsOnTable = [];
        $this->deck = Deck::create($event->deckId());
        $this->rules = new Brisca();
    }

    public function applyCardWasDealt(CardWasDealt $cardWasDealt): void
    {
        $player = $this->getPlayer($cardWasDealt->playerId());
        $player->addCardToHand($cardWasDealt->card());
    }

    public function applyCardWasPlayed(CardWasPlayed $cardWasPlayed): void
    {
        $player = $this->getPlayer($cardWasPlayed->playerId());
        $player->playCard($cardWasPlayed->card());
        $this->cardsOnTable[$cardWasPlayed->playerId()->value()] = $cardWasPlayed->card();
        $this->currentPlayerId = $this->getNextPlayer();
    }

    /** @throws Exception\InvalidNumberOfWonCardsException */
    public function applyHandWasWon(HandWasWon $handWasWon): void
    {
        $player = $this->getPlayer($handWasWon->playerId());
        $player->addCardToWonCards($handWasWon->cards());
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

    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'players' => array_map(
                static fn(Player $player) => $player->toArray(),
                $this->players,
            ),
            'deck' => $this->deck->toArray(),
            'currentPlayerId' => $this->currentPlayerId->value(),
            'cardsOnTable' => array_map(
                static fn(Card $card) => [$card->suite()->value(), $card->rank()->value()],
                $this->cardsOnTable
            ),
        ];
    }
}
