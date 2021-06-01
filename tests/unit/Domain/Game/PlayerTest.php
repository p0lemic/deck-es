<?php

declare(strict_types=1);

namespace Deck\Tests\unit\Domain\Game;

use Deck\Domain\Game\Card;
use Deck\Domain\Game\Exception\CardPlayedNotInPlayerHand;
use Deck\Domain\Game\Exception\InvalidNumberOfWonCardsException;
use Deck\Domain\Game\Player;
use Deck\Domain\Game\Rank;
use Deck\Domain\Game\Suite;
use Deck\Domain\User\PlayerId;
use PHPUnit\Framework\TestCase;

class PlayerTest extends TestCase
{
    /** @test */
    public function createANewPlayerFromAnIdAndAnInitialHand(): void
    {
        $playerId = $this->createMock(PlayerId::class);
        $player = Player::create($playerId);

        self::assertCount(0, $player->hand());
        self::assertCount(0, $player->wonCards());
    }

    /** @test */
    public function canAddCardsToPlayerHand(): void
    {
        $playerId = $this->createMock(PlayerId::class);
        $player = Player::create($playerId);
        $player->addCardToHand(new Card(new Suite('diams'), new Rank('A')));
        $player->addCardToHand(new Card(new Suite('spades'), new Rank('3')));
        $player->addCardToHand(new Card(new Suite('clubs'), new Rank('5')));

        self::assertCount(3, $player->hand());
        self::assertCount(0, $player->wonCards());
    }

    /** @test */
    public function playerCanPlayACardFromHerHandOrShouldFail(): void
    {
        $playerId = $this->createMock(PlayerId::class);
        $player = Player::create($playerId);
        $player->addCardToHand(new Card(new Suite('diams'), new Rank('A')));
        $player->addCardToHand(new Card(new Suite('spades'), new Rank('3')));
        $player->addCardToHand(new Card(new Suite('clubs'), new Rank('5')));

        $player->playCard(new Card(new Suite('diams'), new Rank('A')));
        self::assertCount(2, $player->hand());

        $this->expectException(CardPlayedNotInPlayerHand::class);

        $player->playCard(new Card(new Suite('spades'), new Rank('A')));
    }

    /** @test */
    public function playerCanWonCards(): void
    {
        $playerId = $this->createMock(PlayerId::class);
        $player = Player::create($playerId);

        $player->addCardToWonCards(
            [
                new Card(new Suite('spades'), new Rank('A')),
                new Card(new Suite('clubs'), new Rank('K')),
            ]
        );
        self::assertCount(2, $player->wonCards());
        self::assertEquals(15, $player->score());
    }

    /** @test */
    public function playerCanNotOnlyWonOneCard(): void
    {
        $playerId = $this->createMock(PlayerId::class);
        $player = Player::create($playerId);

        $this->expectException(InvalidNumberOfWonCardsException::class);
        $player->addCardToWonCards(
            [
                new Card(new Suite('spades'), new Rank('A')),
            ]
        );
    }
}
