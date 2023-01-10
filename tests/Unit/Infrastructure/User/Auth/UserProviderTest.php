<?php

namespace Deck\Tests\Unit\Infrastructure\User\Auth;

use Deck\Domain\User\PlayerId;
use Deck\Domain\User\PlayerReadModelRepositoryInterface;
use Deck\Infrastructure\User\Auth\UserProvider;
use PHPUnit\Framework\TestCase;

class UserProviderTest extends TestCase
{
    private readonly PlayerReadModelRepositoryInterface $playerRepository;
    private readonly UserProvider $sut;

    public function setUp(): void
    {
        $this->playerRepository = $this->createMock(PlayerReadModelRepositoryInterface::class);
        $this->sut = new UserProvider($this->playerRepository);
    }

    public function testLoadUserByUsername(): void
    {
        $this->playerRepository->expects($this->once())
            ->method('getCredentialsByEmail')
            ->willReturn(
                [
                    PlayerId::create(),
                    'email@email.com',
                    'asdfghjkl',
                ]
            );

        $auth = $this->sut->loadUserByUsername('email@email.com');

        $this->assertEquals('email@email.com', $auth->getUserIdentifier());
    }

    public function testLoadUserByIdentifier(): void
    {
        $this->playerRepository->expects($this->once())
            ->method('getCredentialsByEmail')
            ->willReturn([
                    PlayerId::create(),
                    'email@email.com',
                    'asdfghjkl',
                ]
            );

        $auth = $this->sut->loadUserByIdentifier('email@email.com');

        $this->assertEquals('email@email.com', $auth->getUserIdentifier());
    }
}
