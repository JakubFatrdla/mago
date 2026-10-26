<?php

declare(strict_types=1);

function take_int(int $value): void
{
}

/**
 * @param non-empty-array<string, mixed> $value
 */
function take_non_empty(array $value): void
{
}

/**
 * @param array<string, string> $general
 */
function required_key_widens(array $general): void
{
    $merged = array_merge(['a' => 1], $general);
    take_int($merged['a']); // @mago-expect analysis:possibly-invalid-argument

    $merged = array_merge($general, ['a' => 1]);
    take_int($merged['a']);
}

/**
 * @param array{a?: int} $optional
 * @param array{b?: int} $otherOptional
 * @param array<string, string> $general
 */
function optional_key_is_not_non_empty(array $optional, array $otherOptional, array $general): void
{
    take_non_empty(array_merge($optional, $general)); // @mago-expect analysis:possibly-invalid-argument
    take_non_empty(array_merge($optional, $otherOptional)); // @mago-expect analysis:possibly-invalid-argument
}

/**
 * @param array<string, mixed> $info
 */
function merge_widens_without_overwrite(bool $withReferrer, string $referrer, array $info): void
{
    $updateData = [];
    if ($withReferrer) {
        $updateData['referrer'] = $referrer;
    }

    $updateData = array_merge($updateData, $info);
    echo count($updateData);
}
