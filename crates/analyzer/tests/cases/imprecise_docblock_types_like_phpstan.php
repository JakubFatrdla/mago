<?php

declare(strict_types=1);

namespace ImpreciseDocblockTypesLikePhpstan;

interface Source
{
    /**
     * @return array
     *
     * @mago-expect analysis:imprecise-type
     */
    public function rows(): array;

    /**
     * @param array|string $data
     *
     * @mago-expect analysis:imprecise-type
     */
    public function check(mixed $data): void;

    /**
     * @param array<string, mixed> $typed
     */
    public function typed(array $typed): void;
}

// @mago-expect analysis:imprecise-type(6)
/**
 * @property-read array $plain
 * @property-read array<string, mixed> $typedTag
 * @property list $listed
 * @property-write iterable $written
 * @property array<int, array> $nested
 * @property array|mixed $absorbed
 * @method never go(string $code, mixed $destination = null, array $args = [])
 * @method array give()
 * @method list<string> fine(array<mixed> $x)
 */
final class Tags
{
}

final class Signatures implements Source
{
    /** @var array<int, array> */
    public array $nestedProperty = []; // @mago-expect analysis:imprecise-type

    /** @var array dimensions */
    public array $describedProperty = []; // @mago-expect analysis:imprecise-type

    /** @var array<string, mixed> */
    public array $typedProperty = [];

    /** @var array<mixed> */
    public array $explicitMixed = [];

    /**
     * @param array<array>|\Traversable<mixed> $data
     * @param array<array{'prefix': string|null, 'omit': array|null}> $config
     * @param array $json
     * @param non-empty-array $nonEmpty
     * @param iterable $loop
     * @param array{a: int, ...} $unsealed
     * @param array<int, list<string>> $deep
     *
     * @mago-expect analysis:imprecise-type(5)
     */
    public function __construct(
        array|\Traversable $data,
        array $config,
        array $json,
        array $nonEmpty,
        iterable $loop,
        array $unsealed,
        array $deep,
    ) {}

    /**
     * @return array
     *
     * @mago-expect analysis:imprecise-type
     */
    public function described(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    public function typedReturn(): array
    {
        return [];
    }

    /**
     * @mago-expect analysis:imprecise-type
     */
    public function rows(): array
    {
        return [];
    }

    /**
     * @mago-expect analysis:imprecise-type
     */
    public function check(mixed $data): void
    {
    }

    public function typed(array $typed): void
    {
    }

    /**
     * @param array $noNative
     *
     * @mago-expect analysis:imprecise-type,missing-parameter-type
     */
    public function docOnly($noNative): void
    {
    }

    /**
     * @param array $values
     *
     * @mago-expect analysis:docblock-type-mismatch
     */
    public function incompatible(\ArrayObject $values): void
    {
    }

    /**
     * @template K
     * @template V
     *
     * @param array<K, V> $items
     *
     * @return array<K, V>
     *
     * @mago-expect analysis:docblock-type-mismatch(2)
     */
    public function templated(array $items): array
    {
        return $items;
    }
}
