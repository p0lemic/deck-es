<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Persistence\Type\Shared;

use Deck\Domain\Shared\ValueObject\DateTime;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class DoctrineDateTimeType extends Type
{
    public const NAME = 'custom_datetime';

    public function getSQLDeclaration(
        array $column,
        AbstractPlatform $platform
    ): string {
        return $platform->getDateTimeTypeDeclarationSQL([]);
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
    ): DateTime {
        return DateTime::fromString($value);
    }
}
