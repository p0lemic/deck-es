<?php

declare(strict_types=1);

namespace Deck\Infrastructure\Persistence\Type\User;

use Deck\Domain\User\PlayerId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use function json_decode;
use function json_encode;

final class DoctrinePlayerIdArrayType extends Type
{
    public const NAME = 'players_id_array';

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
     * @param PlayerId $value
     * @param AbstractPlatform $platform
     * @return string
     */
    public function convertToDatabaseValue(
        $value,
        AbstractPlatform $platform
    ): string {
        $playersIds = [];
        /** @var PlayerId $playerId */
        foreach ($value as $playerId) {
            $playersIds[] = $playerId->value();
        }
        return json_encode($playersIds);
    }

    /**
     * @param string $value
     * @param AbstractPlatform $platform
     * @return PlayerId[]
     */
    public function convertToPHPValue(
        $value,
        AbstractPlatform $platform
    ): array {
        $playersIds = [];

        foreach (json_decode($value, true) as $player) {
            $playersIds[] = PlayerId::fromString($player);
        }

        return $playersIds;
    }
}
