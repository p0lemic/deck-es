<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Persistence\Type\User;

use Deck\Domain\User\PlayerId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class DoctrinePlayerIdType extends Type
{
    public const NAME = 'player_id';

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
     * @param PlayerId $value
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
     * @return PlayerId
     */
    public function convertToPHPValue(
        $value,
        AbstractPlatform $platform
    ): PlayerId {
        return PlayerId::fromString($value);
    }
}
