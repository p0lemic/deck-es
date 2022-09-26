<?php

declare(strict_types=1);

namespace Deck\Domain\Game;

use Broadway\EventSourcing\SimpleEventSourcedEntity;
use Deck\Domain\Game\Exception\InvalidNumberOfWonCardsException;
use Deck\Domain\Game\Exception\PlayedCardNotInPlayerHandException;
use Deck\Domain\User\PlayerId;

class Player extends SimpleEventSourcedEntity
{
    private PlayerId $id;
    /** @var Card[] */
    private array $hand;
    /** @var Card[] */
    private array $wonCards;

    private function __construct(
        PlayerId $playerId
    ) {
        $this->id = $playerId;
        $this->hand = [];
        $this->wonCards = [];
    }

    public static function create(PlayerId $playerId): self
    {
        return new self($playerId);
    }

    public function playerId(): PlayerId
    {
        return $this->id;
    }

    public function hand(): array
    {
        return $this->hand;
    }

    public function wonCards(): array
    {
        return $this->wonCards;
    }

    public function addCardToHand(Card $card): void
    {
        $this->hand[] = $card;
    }

    public function playCard(Card $card): void
    {
        foreach ($this->hand as $index => $cardInHand) {
            if ($card->equals($cardInHand)) {
                unset($this->hand[$index]);

                return;
            }
        }

        throw PlayedCardNotInPlayerHandException::notFound();
    }

    public function score(): int
    {
        $points = 0;

        foreach ($this->wonCards as $card) {
            $points += $card->points();
        }

        return $points;
    }

    public function addCardToWonCards(array $cards): void
    {
        if (count($cards) !== 2) {
            throw InvalidNumberOfWonCardsException::notValid();
        }

        foreach ($cards as $card) {
            $this->wonCards[] = $card;
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->playerId()->__toString(),
            'hand' => array_map(
                static fn (Card $card) => [$card->suite()->value(), $card->rank()->value()],
                $this->hand
            ),
            'wonCards' => array_map(
                static fn (Card $card) => [$card->suite()->value(), $card->rank()->value()],
                $this->wonCards
            ),
        ];
    }
}
