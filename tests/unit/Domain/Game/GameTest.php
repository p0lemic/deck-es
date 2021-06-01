<?php

namespace Deck\Tests\unit\Domain\Game;

use Broadway\Domain\DomainMessage;
use Deck\Domain\Game\Brisca;
use Deck\Domain\Game\DeckId;
use Deck\Domain\Game\Event\GameWasCreated;
use Deck\Domain\Game\Game;
use Deck\Domain\Game\GameId;
use Deck\Domain\Game\Player;
use Deck\Domain\User\PlayerId;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    /**
     * @test
     */
    public function given_a_player_list_then_should_create_a_deck(): void
    {
        $players = [PlayerId::create(), PlayerId::create()];

        $game = Game::create(GameId::create(), DeckId::create(), $players, new Brisca());

        self::assertNotNull($game->getAggregateRootId());
        self::assertCount(count($players), $game->players());

        $events = $game->getUncommittedEvents();

        self::assertCount(1, $events->getIterator(), 'Only one event should be in the buffer');

        /** @var DomainMessage $event */
        $event = $events->getIterator()->current();

        self::assertInstanceOf(GameWasCreated::class, $event->getPayload(), 'First event should be GameWasCreated');
    }

    /**
     * @test
     */
    public function given_a_deck_and_a_player_she_can_draw_a_card(): void
    {
        $playerOne = PlayerId::create();
        $playerTwo = PlayerId::create();

        $players = [$playerOne, $playerTwo];

        $game = Game::create(GameId::create(), DeckId::create(), $players, new Brisca());
        $game->initGame();
        $game->playerDraw(Player::create($playerOne));

        self::assertCount(33, $game->deck()->cards());
    }
}
