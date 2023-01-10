<?php

namespace Deck\Tests\Unit\Application\User;

use Deck\Application\User\SignInCommand;
use Deck\Application\User\SignInHandler;
use Deck\Domain\User\Exception\InvalidCredentialsException;
use Deck\Domain\User\Player;
use Deck\Domain\User\PlayerId;
use Deck\Domain\User\PlayerReadModelRepositoryInterface;
use Deck\Domain\User\PlayerRepositoryInterface;
use PHPUnit\Framework\TestCase;

class SignInHandlerTest extends TestCase
{
    private readonly PlayerRepositoryInterface $playerRepository;
    private readonly PlayerReadModelRepositoryInterface $playerReadModelRepository;
    private readonly SignInHandler $sut;

    public function setUp(): void
    {
        $this->playerRepository = $this->createMock(PlayerRepositoryInterface::class);
        $this->playerReadModelRepository = $this->createMock(PlayerReadModelRepositoryInterface::class);
        $this->sut = new SignInHandler($this->playerRepository, $this->playerReadModelRepository);
    }

    public function testSignInWithExistingUser(): void
    {
        $command = new SignInCommand("user@user.com", "123456");
        $playerId = PlayerId::create();
        $player = $this->createMock(Player::class);

        $this->playerReadModelRepository->expects($this->once())
            ->method('existsEmail')
            ->willReturn($playerId);

        $this->playerRepository->expects($this->once())
            ->method('get')
            ->with($playerId)
            ->willReturn($player);

        $this->playerRepository->expects($this->once())
            ->method('store')
            ->with($player);

        $this->sut->handle($command);
    }

    public function testSignInWithAnUnregisteredUser(): void
    {
        $this->expectException(InvalidCredentialsException::class);
        $this->expectExceptionMessage('Invalid credentials provided.');

        $command = new SignInCommand("user@user.com", "123456");

        $this->playerReadModelRepository->expects($this->once())
            ->method('existsEmail')
            ->willReturn(null);

        $this->sut->handle($command);
    }
}
