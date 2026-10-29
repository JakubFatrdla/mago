<?php

declare(strict_types=1);

final class Keys
{
    public const array Allowed = ['from', 'to', 'order'];

    /**
     * @return list<array{string}>
     */
    public static function provideAllowed(): array
    {
        return array_map(static fn(string $key): array => [$key], self::Allowed);
    }

    /**
     * @return list<array{string}>
     */
    public static function provideAllowedUntyped(): array
    {
        return array_map(static fn(string $key) => [$key], self::Allowed);
    }

    /**
     * @return list<non-empty-string>
     */
    public static function provideUpper(): array
    {
        return array_map(static fn(string $key): string => strtoupper($key) . '!', self::Allowed);
    }
}
