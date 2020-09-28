<?php

namespace Deck\Domain\Game;

use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Deck\Domain\Game\Event\CardWasDealt;
use Deck\Domain\Game\Event\GameWasCreated;
use Deck\Domain\Game\Exception\CardsNumberInUseNotValidException;
use Deck\Domain\Game\Exception\PlayerNotAllowedToDraw;
use Deck\Domain\Shared\Exception\DateTimeException;
use Deck\Domain\Shared\ValueObject\DateTime;
use Deck\Domain\User\PlayerId;
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
    private const MAX_CARDS_IN_PLAYER_HAND = 3;
    private GameId $id;
    private Deck $deck;
    /** @var Player[] */
    private array $players;

    public static function create(
        GameId $gameId,
        DeckId $deckId,
        array $players
    ): self {
        $game = new self();
        $game->apply(new GameWasCreated($gameId, $players, $deckId, DateTime::now()));

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
        return $this->players[$playerId->value()] ?? null;
    }

    public function initGame(): void
    {
        $this->deck->shuffleCards();

        foreach($this->players() as $player) {
            $this->dealInitialHand($player);
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
        if (self::MAX_CARDS_IN_PLAYER_HAND >= count($player->hand())) {
            throw PlayerNotAllowedToDraw::isFull();
        }

        $this->assertTotalCardsInGameAreConsistency();

        $card = $this->deck->draw();

        $this->apply(new CardWasDealt($player->playerId(), $card, DateTime::now()));
    }

    private function dealInitialHand(Player $player) {
        for($i = 0; $i < self::MAX_CARDS_IN_PLAYER_HAND; $i++) {
            $this->playerDraw($player);
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

    public function applyGameWasCreated(GameWasCreated $event): void
    {
        $this->id = $event->aggregateId();
        foreach ($event->players() as $playerId) {
            $this->players[$playerId->value()] = Player::create($playerId);
        }
        $this->deck = Deck::create($event->deckId());
    }

    public function applyCardWasDealt(CardWasDealt $cardWasDealt): void
    {
        $player = $this->players[$cardWasDealt->playerId()->value()];

        $player->addCardToHand($cardWasDealt->card());
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
