<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Persistence\Type\Game;

use Deck\Domain\Game\GameId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class DoctrineGameIdType extends Type
{
    public const NAME = 'game_id';

    /**
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @param mixed[] $column The field declaration.
     * @param AbstractPlatform $platform The currently used database platform.
     *
     * @return string
     */
    public function getSQLDeclaration(
        array $column,
        AbstractPlatform $platform
    ): string {
        return $platform->getVarcharTypeDeclarationSQL([]);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
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
     * @param GameId $value
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
     * @return GameId
     */
    public function convertToPHPValue(
        $value,
        AbstractPlatform $platform
    ): GameId {
        return GameId::fromString($value);
    }
}
