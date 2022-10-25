<?php

namespace Deck\Tests\Unit\Domain\Game;

use Broadway\Domain\DomainMessage;
use Deck\Domain\Game\Brisca;
use Deck\Domain\Game\DeckId;
use Deck\Domain\Game\Event\CardWasAddedToDeck;
use Deck\Domain\Game\Event\CardWasDealt;
use Deck\Domain\Game\Event\CardWasDrawn;
use Deck\Domain\Game\Event\CardWasPlayed;
use Deck\Domain\Game\Event\GameWasCreated;
use Deck\Domain\Game\Event\HandWasWon;
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
        $game = $gameFactory->createNewGame(GameId::create(), DeckId::create(), $players, new Brisca());
        $game->initGame();

        self::assertCount(34, $game->deck()->cards());

        $events = $game->getUncommittedEvents();

        self::assertCount(53, $events->getIterator(), '41 events should be in the buffer');

        /** @var DomainMessage $event */
        $event = $events->getIterator()->offsetGet($events->getIterator()->count() - 1);

        self::assertInstanceOf(CardWasDealt::class, $event->getPayload(), 'Last event should be CardWasDealt');

    }

    /** @test */
    public function whenPlayerPlayACard(): void
    {
        $players = [PlayerId::create(), PlayerId::create()];

        $gameFactory = new GameFactory();
        $game = $gameFactory->createNewGame(GameId::create(), DeckId::create(), $players, new Brisca());
        $game->initGame();
        $player = $game->getPlayer($players[0]);
        $hand = $player->hand();
        $game->playCard($player, next($hand));

        $events = $game->getUncommittedEvents();
        /** @var DomainMessage $event */
        $event = $events->getIterator()->offsetGet($events->getIterator()->count() - 1);

        self::assertInstanceOf(CardWasPlayed::class, $event->getPayload(), 'Last event should be CardWasPlayed');

    }

    /** @test */
    public function whenTwoPlayersPlayACardTheTurnShouldBeResolve(): void
    {
        $players = [
            PlayerId::fromString('87eb5496-8d96-4e2d-8c3e-a9adced80bbb'),
            PlayerId::fromString('fca90bd6-8a37-43ee-b200-86f7a638bb7e')
        ];

        $gameFactory = new GameFactory();
        $game = $gameFactory->createNewGame(GameId::create(), DeckId::create(), $players, new Brisca());
        $game->initGame();

        $player1 = $game->getPlayer($players[0]);
        $hand = $player1->hand();
        $game->playCard($player1, next($hand));

        $player2 = $game->getPlayer($players[1]);
        $hand = $player2->hand();
        $game->playCard($player2, next($hand));

        $events = $game->getUncommittedEvents();
        /** @var DomainMessage $event */
        $event = $events->getIterator()->offsetGet($events->getIterator()->count() - 1);

        self::assertInstanceOf(HandWasWon::class, $event->getPayload(), 'Last event should be CardWasPlayed');
    }
}
