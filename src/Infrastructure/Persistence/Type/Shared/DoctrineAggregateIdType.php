<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Persistence\Type\Shared;

use Deck\Domain\Shared\AggregateId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class DoctrineAggregateIdType extends Type
{
    public const NAME = 'aggregate_id';

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
    ): AggregateId {
        return AggregateId::fromString($value);
    }
}
