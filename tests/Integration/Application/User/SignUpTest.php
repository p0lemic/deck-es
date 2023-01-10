<?php

namespace Deck\Tests\Integration\Application\User;

use Deck\Application\User\SignInCommand;
use Deck\Application\User\SignInHandler;
use Deck\Domain\User\Exception\InvalidCredentialsException;
use Deck\Domain\User\PlayerReadModelRepositoryInterface;
use Deck\Domain\User\PlayerRepositoryInterface;
use Doctrine\DBAL\Connection;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SignUpTest extends KernelTestCase
{
    private Connection $connection;
    private ContainerInterface $container;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->container = static::getContainer();
//        $this->connection = $this->container->get(Connection::class);
//        $this->connection->beginTransaction();
        $this->sut = new SignInHandler(
            $this->container->get(PlayerRepositoryInterface::class),
            $this->container->get(PlayerReadModelRepositoryInterface::class),
        );
    }

    protected function tearDown(): void
    {
//        $this->connection->rollBack();
    }

    public function testLoginWithInvalidUser()
    {
        $this->expectException(InvalidCredentialsException::class);
        $this->expectExceptionMessage('Invalid credentials provided.');

        $this->sut->handle(new SignInCommand('invalid-user@gmail.com', '123456'));
    }

    public function testLoginWithValidUser()
    {
        $this->sut->handle(new SignInCommand('valid-user@gmail.com', '123456'));
    }
}
