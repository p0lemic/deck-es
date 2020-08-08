<?php

declare(strict_types=1);

namespace Deck\Tests\unit\Domain\User;

use Deck\Domain\User\Player;
use Deck\Domain\User\Specification\UniqueEmailSpecificationInterface;
use Deck\Domain\User\ValueObject\Auth\Credentials;
use Deck\Domain\User\ValueObject\Email;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class PlayerTest extends TestCase
{
    /** @var MockObject|Credentials */
    private $credentials;
    /** @var MockObject|UniqueEmailSpecificationInterface */
    private $emailSpecification;

    public function setUp(): void
    {
        $this->credentials = $this->createMock(Credentials::class);
        $this->emailSpecification = $this->createMock(UniqueEmailSpecificationInterface::class);

        parent::setUp();
    }

    public function testCreateNewPlayer(): void
    {
        $this->credentials->expects($this->once())->method('email')->willReturn(Email::fromString('fake@email.com'));
        $this->emailSpecification->expects($this->once())->method('isUnique')->willReturn(true);

        $sut = Player::create($this->credentials, $this->emailSpecification);

        $this->assertNotNull($sut->getAggregateRootId());
    }
}
