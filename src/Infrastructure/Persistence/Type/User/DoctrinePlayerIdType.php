<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Persistence\Type\User;

use Deck\Domain\User\PlayerId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class DoctrinePlayerIdType extends Type
{
    public const NAME = 'player_id';

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
        return $value->value();
    }

    public function convertToPHPValue(
        $value,
        AbstractPlatform $platform
    ): PlayerId {
        return PlayerId::fromString($value);
    }
}
