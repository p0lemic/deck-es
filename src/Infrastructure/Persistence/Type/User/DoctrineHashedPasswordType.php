<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Persistence\Type\User;

use Deck\Domain\User\ValueObject\Auth\HashedPassword;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class DoctrineHashedPasswordType extends Type
{
    public const NAME = 'hashed_password';

    public function getSQLDeclaration(
        array $column,
        AbstractPlatform $platform
    ): string {
        return $platform->getStringTypeDeclarationSQL([]);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToDatabaseValue(
        $value,
        AbstractPlatform $platform
    ): string {
        return $value->toString();
    }

    public function convertToPHPValue(
        $value,
        AbstractPlatform $platform
    ): HashedPassword {
        return HashedPassword::fromHash($value);
    }
}
