<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Persistence\Type\Table;

use Deck\Domain\Table\TableId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class DoctrineTableIdType extends Type
{
    public const NAME = 'table_id';

    /**
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @param mixed[] $fieldDeclaration The field declaration.
     * @param AbstractPlatform $platform The currently used database platform.
     *
     * @return string
     */
    public function getSQLDeclaration(
        array $fieldDeclaration,
        AbstractPlatform $platform
    ): string {
        return $platform->getVarcharTypeDeclarationSQL([]);
    }

    /**
     * Gets the name of this type.
     *
     * @return string
     *
     */
    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @param TableId $value
     * @param AbstractPlatform $platform
     * @return string
     */
    public function convertToDatabaseValue(
        $value,
        AbstractPlatform $platform
    ): string {
        return $value->value();
    }

    /**
     * @param string $value
     * @param AbstractPlatform $platform
     * @return TableId
     */
    public function convertToPHPValue(
        $value,
        AbstractPlatform $platform
    ): TableId {
        return TableId::fromString($value);
    }
}
