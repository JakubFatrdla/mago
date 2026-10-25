<?php

declare(strict_types=1);

namespace LateStaticConstantDeclaredType;

/**
 * @param array<string, int> $_value
 */
function take_map(array $_value): void
{
}

function take_shape(array $_value): void
{
}

class Labels
{
    public const array TYPED = ['renter' => ['a' => 'x']];

    public const UNTYPED = ['renter' => 1];

    /** @var array<string, int> */
    public const DOCUMENTED = ['renter' => 1];

    final public const FINAL = ['renter' => 1];

    public function typed(string $key): bool
    {
        // @mago-expect analysis:mixed-array-access
        return isset($this::TYPED['renter'][$key]);
    }

    public function typed_by_static(string $key): bool
    {
        // @mago-expect analysis:mixed-array-access
        return isset(static::TYPED['renter'][$key]);
    }

    public function by_self(string $key): bool
    {
        return isset(self::TYPED['renter'][$key]);
    }

    public function self_and_static_differ(string $key): string
    {
        // @mago-expect analysis:mixed-array-access
        if (isset(static::TYPED['renter'][$key])) {
            return 'static';
        }

        if (isset(self::TYPED['renter'][$key])) {
            return 'self';
        }

        return 'none';
    }

    public function untyped(): void
    {
        // @mago-expect analysis:mixed-argument
        take_shape(static::UNTYPED);
    }

    public function documented(): void
    {
        take_map($this::DOCUMENTED);
    }

    public function final_constant(): void
    {
        take_shape(static::FINAL);
    }
}

final class Sealed
{
    public const UNTYPED = ['renter' => 1];

    public function untyped(): void
    {
        take_shape(static::UNTYPED);
    }
}
