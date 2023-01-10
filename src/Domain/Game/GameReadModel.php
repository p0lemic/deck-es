<?php

namespace Deck\Domain\Game;

class GameReadModel
{
    private string $id;
    private array $players;
    private ?string $currentPlayerId;
    private array $deck;
    private array $cardsOnTable;

    public function __construct(
        string $gameId,
        array $players,
        ?string $currentPlayer,
        array $deck = [],
        array $cardsOnTable = []
    ) {
        $this->id = $gameId;
        $this->players = $players;
        $this->currentPlayerId = $currentPlayer;
        $this->deck = $deck;
        $this->cardsOnTable = $cardsOnTable;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function players(): array
    {
        return $this->players;
    }

    public function deck(): array
    {
        return $this->deck;
    }

    public function currentPlayerId(): ?string
    {
        return $this->currentPlayerId;
    }

    public function cardsOnTable(): array
    {
        return $this->cardsOnTable;
    }

    public function addCardToDeck(array $card): void
    {
        $this->deck[] = $card;
    }

    public function addCardToHand(
        string $playerId,
        array $card
    ): void {
        foreach ($this->players as $player) {
            if ($player->id === $playerId) {
                $player->hand[] = $card;
            }
        }
    }

    public function playCard(
        string $playerId,
        array $card
    ): void {
        foreach ($this->players as $player) {
            if ($player->id === $playerId) {
                foreach ($player->hand as $index => $cardInHand) {
                    if ($card['suite'] === $cardInHand['suite'] && $card['rank'] === $cardInHand['rank']) {
                        unset($player->hand[$index]);

                        return;
                    }
                }
            } else {
                $this->currentPlayerId = $playerId;
            }
        }

        $this->cardsOnTable[$playerId] = $card;
    }

    public function wonCard(
        string $playerId,
        array $cards
    ): void {
        foreach ($this->players as $player) {
            if ($player->id === $playerId) {
                $player->addCardToWonCards($cards);
            }
        }
    }

    public function normalize(): array
    {
        return [
            'id' => $this->id,
            'players' => $this->players,
            'deck' => $this->deck,
            'currentPlayerId' => $this->currentPlayerId,
            'cardsOnTable' => array_map(
                static fn (Card $card) => [$card->suite()->value(), $card->rank()->value()],
                $this->cardsOnTable
            ),
        ];
    }

    /**
     * @param Card[] $deck
     * @param Card[] $cardsOnTable
     */
    public static function denormalize(
        string $id,
        array $players,
        string $currentPlayer,
        array $deck,
        array $cardsOnTable
    ): self {
        return new self(
            $id,
            $players,
            $currentPlayer,
            $deck,
            $cardsOnTable
        );
    }

    /** @param Card[] $deck */
    private function addDeck(array $deck): void
    {
        $this->deck = $deck;
    }

    /** @param Card[] $cardsOnTable */
    private function addCardsOnTable(array $cardsOnTable): void
    {
        $this->cardsOnTable = $cardsOnTable;
    }
}
