<?php

declare(strict_types=1);

namespace Deck\Domain\Game;

use Broadway\EventSourcing\SimpleEventSourcedEntity;
use Deck\Domain\Game\Event\CardWasDeal;
use Deck\Domain\Shared\ValueObject\DateTime;
use Deck\Domain\User\PlayerId;

class Player extends SimpleEventSourcedEntity
{
    private PlayerId $id;
    /** @var Card[] */
    private array $hand;
    /** @var Card[] */
    private array $wonCards;

    public function __construct(
        PlayerId $playerId,
        array $hand
    ) {
        $this->id = $playerId;
        $this->hand = $hand;
        $this->wonCards = [];
    }

    public static function create(PlayerId $playerId): self
    {
        return new self($playerId, []);
    }

    public function playerId(): PlayerId
    {
        return $this->id;
    }

    public function hand(): array
    {
        return $this->hand;
    }

    public function addCardToHand(Card $card): void
    {
        $this->hand[] = $card;
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


