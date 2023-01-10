<?php

namespace Deck\Tests\Unit\Application\User;

use Deck\Application\User\SignUpCommand;
use Deck\Application\User\SignUpHandler;
use Deck\Domain\User\Exception\EmailAlreadyExistException;
use Deck\Domain\User\PlayerId;
use Deck\Domain\User\PlayerReadModelRepositoryInterface;
use Deck\Domain\User\PlayerRepositoryInterface;
use Deck\Domain\User\Specification\UniqueEmailSpecificationInterface;
use Deck\Infrastructure\User\Specification\UniqueEmailSpecification;
use PHPUnit\Framework\TestCase;

class SignUpHandlerTest extends TestCase
{
    private readonly PlayerRepositoryInterface $playerRepository;
    private readonly PlayerReadModelRepositoryInterface $playerReadModelRepository;
    private readonly UniqueEmailSpecificationInterface $uniqueEmailSpecification;
    private readonly SignUpHandler $sut;

    protected function setUp(): void
    {
        $this->playerRepository = $this->createMock(PlayerRepositoryInterface::class);
        $this->playerReadModelRepository = $this->createMock(PlayerReadModelRepositoryInterface::class);
        $this->uniqueEmailSpecification = new UniqueEmailSpecification($this->playerReadModelRepository);
        $this->sut = new SignUpHandler($this->playerRepository, $this->uniqueEmailSpecification);
    }

    public function testSignUpNewPlayer(): void
    {
        $command = new SignUpCommand("user@user.com", "123456");

        $this->playerRepository->expects($this->once())
            ->method('store');

        $this->sut->handle($command);
    }

    public function testPlayerIsNotCreatedWhenEmailIsNotUnique(): void
    {
        $this->expectException(EmailAlreadyExistException::class);
        $this->expectExceptionMessage('Email user@user.com already exists');

        $command = new SignUpCommand("user@user.com", "123456");
        $this->playerReadModelRepository
            ->method('existsEmail')
            ->willReturn(PlayerId::create());

        $this->sut->handle($command);
    }
}
