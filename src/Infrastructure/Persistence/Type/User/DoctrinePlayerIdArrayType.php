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

    public function getName(): string
    {
        return self::NAME;
    }

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
