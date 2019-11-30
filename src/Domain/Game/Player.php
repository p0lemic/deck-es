<?php

declare(strict_types=1);

namespace Deck\Domain\Game;

use Broadway\EventSourcing\SimpleEventSourcedEntity;
use Deck\Domain\Game\Event\CardWasDeal;
use Deck\Domain\User\PlayerId;

class Player extends SimpleEventSourcedEntity
{
    /** @var PlayerId */
    private $playerId;
    /** @var Card[] */
    private $hand;
    /** @var Card[] */
    private $wonCards;

    public function __construct(
        PlayerId $playerId,
        array $hand
    ) {
        $this->playerId = $playerId;
        $this->hand = $hand;
        $this->wonCards = [];
    }

    public static function create(PlayerId $playerId): self
    {
        return new self($playerId, []);
    }

    public function playerId(): PlayerId
    {
        return $this->playerId;
    }

    public function hand(): array
    {
        return $this->hand;
    }

    public function addCardToPlayersHand(Card $card): void
    {
        $this->apply(new CardWasDeal($this->playerId(), $card));
    }

    public function applyCardWasDeal(CardWasDeal $cardWasDeal): void
    {
        $this->hand[] = $cardWasDeal->card();
    }

    public function score(): int
    {
        $points = 0;

        foreach ($this->wonCards as $card) {
            $points += $card->points();
        }

        return $points;
    }
}


