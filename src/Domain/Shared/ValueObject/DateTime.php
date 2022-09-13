<?php

declare(strict_types=1);

namespace Deck\Domain\Shared\ValueObject;

use DateTimeImmutable;
use Deck\Domain\Shared\Exception\DateTimeException;

class DateTime
{
    private const FORMAT = 'Y-m-d\TH:i:s.uP';

    private DateTimeImmutable $dateTime;

    private function __construct(string $dateTime = '')
    {
        try {
            $this->dateTime = new DateTimeImmutable($dateTime);
        } catch (\Exception $e) {
            throw new DateTimeException($e);
        }
    }

    /**
     * @throws DateTimeException
     */
    public static function now(): self
    {
        return self::create();
    }

    /**
     * @param string $dateTime
     * @return DateTime
     * @throws DateTimeException
     */
    public static function fromString(string $dateTime): DateTime
    {
        return self::create($dateTime);
    }

    /**
     * @param string $dateTime
     * @return DateTime
     * @throws DateTimeException
     */
    private static function create(string $dateTime = ''): DateTime
    {
        return new self($dateTime);
    }

    public function toString(): string
    {
        return $this->dateTime->format(self::FORMAT);
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toNative(): DateTimeImmutable
    {
        return $this->dateTime;
    }
}
