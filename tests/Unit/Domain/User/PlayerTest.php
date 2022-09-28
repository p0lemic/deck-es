<?php

declare(strict_types=1);

namespace Deck\Tests\Unit\Domain\User;

use Deck\Domain\User\Player;
use Deck\Domain\User\PlayerId;
use Deck\Domain\User\Specification\UniqueEmailSpecificationInterface;
use Deck\Domain\User\ValueObject\Auth\Credentials;
use Deck\Domain\User\ValueObject\Email;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class PlayerTest extends TestCase
{
    private Credentials|MockObject $credentials;
    private UniqueEmailSpecificationInterface|MockObject $emailSpecification;

    public function setUp(): void
    {
        $this->credentials = $this->createMock(Credentials::class);
        $this->emailSpecification = $this->createMock(UniqueEmailSpecificationInterface::class);

        parent::setUp();
    }

    public function testCreateNewPlayer(): void
    {
        $this->credentials->expects(self::once())->method('email')->willReturn(Email::fromString('fake@email.com'));
        $this->emailSpecification->expects(self::once())->method('isUnique')->willReturn(true);

        $sut = Player::create(PlayerId::create(), $this->credentials, $this->emailSpecification);

        self::assertNotNull($sut->getAggregateRootId());
    }
}
