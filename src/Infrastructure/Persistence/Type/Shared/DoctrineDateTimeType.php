<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Persistence\Type\Shared;

use Deck\Domain\Shared\Exception\DateTimeException;
use Deck\Domain\Shared\ValueObject\DateTime;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class DoctrineDateTimeType extends Type
{
    public const NAME = 'datetime';

    /**
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @param mixed[] $fieldDeclaration The field declaration.
     * @param AbstractPlatform $platform The currently used database platform.
     *
     * @return string
     * @throws DBALException
     */
    public function getSQLDeclaration(
        array $fieldDeclaration,
        AbstractPlatform $platform
    ): string {
        return $platform->getDateTimeTypeDeclarationSQL([]);
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
     * @param DateTime $value
     * @param AbstractPlatform $platform
     * @return string
     */
    public function convertToDatabaseValue(
        $value,
        AbstractPlatform $platform
    ): string {
        if ($value instanceof DateTime) {
            return $value->toString();
        }

        return $value->format('Y-m-d H:i:s');
    }

    /**
     * @param string $value
     * @param AbstractPlatform $platform
     * @return DateTime
     * @throws DateTimeException
     */
    public function convertToPHPValue(
        $value,
        AbstractPlatform $platform
    ): DateTime {
        return DateTime::fromString($value);
    }
}
