<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Persistence\Type\Table;

use Deck\Domain\Table\TableId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class DoctrineTableIdType extends Type
{
    public const NAME = 'table_id';

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
    ): TableId {
        return TableId::fromString($value);
    }
}
