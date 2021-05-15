<?php

namespace Deck\Tests\unit\Domain\Game;

use Broadway\Domain\DomainMessage;
use Deck\Domain\Game\DeckId;
use Deck\Domain\Game\Event\CardWasAddedToDeck;
use Deck\Domain\Game\Event\CardWasDealt;
use Deck\Domain\Game\Event\CardWasDrawn;
use Deck\Domain\Game\Event\CardWasPlayed;
use Deck\Domain\Game\Event\GameWasCreated;
use Deck\Domain\Game\GameFactory;
use Deck\Domain\Game\GameId;
use Deck\Domain\Game\Player;
use Deck\Domain\User\PlayerId;
use PHPUnit\Framework\TestCase;
use function next;
use function var_dump;

class GameFactoryTest extends TestCase
{
    /** @test */
    public function newDeckShouldHas40Cards(): void
    {
        $players = [PlayerId::create(), PlayerId::create()];

        $gameFactory = new GameFactory();
        $game = $gameFactory->createNewGame(GameId::create(), DeckId::create(), $players);
        $game->initGame();

        self::assertCount(34, $game->deck()->cards());

        $events = $game->getUncommittedEvents();

        self::assertCount(53, $events->getIterator(), '41 events should be in the buffer');

        /** @var DomainMessage $event */
        $event = $events->getIterator()->offsetGet($events->getIterator()->count() - 1);

        self::assertInstanceOf(CardWasDealt::class, $event->getPayload(), 'Last event should be CardWasDealt');

    }

    /** @test */
    public function whenPlayerDrawCardDeckShouldHaveOneLessCard(): void
    {
        $players = [PlayerId::create(), PlayerId::create()];

        $gameFactory = new GameFactory();
        $game = $gameFactory->createNewGame(GameId::create(), DeckId::create(), $players);
        $game->initGame();

        self::assertCount(34, $game->deck()->cards());
        $game->deck()->draw();
        self::assertCount(33, $game->deck()->cards());

        $events = $game->getUncommittedEvents();
        /** @var DomainMessage $event */
        $event = $events->getIterator()->offsetGet($events->getIterator()->count() - 1);

        self::assertInstanceOf(CardWasDrawn::class, $event->getPayload(), 'Last event should be CardWasDrawn');
    }

    /** @test */
    public function whenPlayerPlayACard(): void
    {
        $players = [PlayerId::create(), PlayerId::create()];

        $gameFactory = new GameFactory();
        $game = $gameFactory->createNewGame(GameId::create(), DeckId::create(), $players);
        $game->initGame();
        $player = $game->getPlayer($players[0]);
        $hand = $player->hand();
        $game->playCard($player, next($hand));

        $events = $game->getUncommittedEvents();
        /** @var DomainMessage $event */
        $event = $events->getIterator()->offsetGet($events->getIterator()->count() - 1);

        self::assertInstanceOf(CardWasPlayed::class, $event->getPayload(), 'Last event should be CardWasPlayed');

    }
}
