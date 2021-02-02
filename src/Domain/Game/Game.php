<?php

namespace Deck\Domain\Game;

use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Deck\Domain\Game\Event\CardWasDealt;
use Deck\Domain\Game\Event\CardWasPlayed;
use Deck\Domain\Game\Event\GameWasCreated;
use Deck\Domain\Game\Exception\CardsNumberInUseNotValidException;
use Deck\Domain\Game\Exception\PlayerNotAllowedToDraw;
use Deck\Domain\Shared\Exception\DateTimeException;
use Deck\Domain\Shared\ValueObject\DateTime;
use Deck\Domain\User\PlayerId;
use function count;
use function usort;

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
    private PlayerId $currentPlayerId;
    /** @var Card[] */
    private array $cardsOnTable;
    private Rules $rules;

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

    private function dealInitialHand(Player $player): void {
        for($i = 0; $i < self::MAX_CARDS_IN_PLAYER_HAND; $i++) {
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
        if (self::MAX_CARDS_IN_PLAYER_HAND >= count($player->hand())) {
            throw PlayerNotAllowedToDraw::isFull();
        }

        $this->assertTotalCardsInGameAreConsistency();

        $card = $this->deck->draw();

        $this->apply(new CardWasDealt($player->playerId(), $card, DateTime::now()));
    }

    public function playCard(Player $player, Card $card): void
    {
        $this->apply(new CardWasPlayed($player->playerId(), $card, DateTime::now()));
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
            $this->currentPlayerId = $this->currentPlayerId ?? $playerId;
        }
        $this->deck = Deck::create($event->deckId());
        $this->rules = new Brisca();
    }

    public function applyCardWasDealt(CardWasDealt $cardWasDealt): void
    {
        $player = $this->players[$cardWasDealt->playerId()->value()];
        $player->addCardToHand($cardWasDealt->card());
    }

    public function applyCardWasPlayed(CardWasPlayed $cardWasPlayed): void
    {
        $player = $this->players[$cardWasPlayed->playerId()->value()];
        $player->playCard($cardWasPlayed->card());
        $this->cardsOnTable[$cardWasPlayed->playerId()->value()] = $cardWasPlayed->card();
        $this->currentPlayerId = $this->getNextPlayer();

        if ($this->areAllCardsPlayed()) {
            $this->resolveTurn();
        }
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

    private function getNextPlayer()
    {
        while($player = current($this->players))
        {
            if($this->currentPlayerId === key($this->players)) {
                return (next($this->players))->playerId() ?? reset($this->players)->playerId();
            }
            next($this->players);
        }
    }

    private function areAllCardsPlayed(): bool
    {
        return count($this->cardsOnTable) === count($this->players);
    }

    private function resolveTurn(): void
    {
        usort($this->cardsOnTable, static fn($a, $b) =>  ($this->rules->resolveHand($a, $b)));
    }
}
