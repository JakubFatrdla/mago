<?php

declare(strict_types=1);

namespace CallableParametersLikePhpstan;

final class Events
{
    /** @var array<callable(mixed): void> */
    public array $onValue = [];

    /** @var array<callable> */
    public array $onAnything = [];

    /** @var array<\Closure> */
    public array $onAnyClosure = [];

    /** @var array<callable(array-key): void> */
    public array $onKey = [];

    /** @var array<callable(int|string): void> */
    public array $onIntOrString = [];

    /** @var array<callable(int): void> */
    public array $onInt = [];

    public function handleInt(int $_value): void
    {
    }

    public function handleMixed(mixed $_value): void
    {
    }
}

/**
 * @param callable(mixed): void $_callback
 */
function take_mixed_callback(callable $_callback): void
{
}

function test_mixed_parameter_accepts_only_mixed(Events $events): void
{
    $events->onValue[] = function (int $_value): void {}; // @mago-expect analysis:invalid-property-assignment-value
}

function test_optional_parameter_accepts_only_mixed(Events $events): void
{
    $events->onValue[] = function (null|int $_value = null): void {}; // @mago-expect analysis:invalid-property-assignment-value
}

function test_method_array_accepts_only_mixed(Events $events): void
{
    $events->onValue[] = [$events, 'handleInt']; // @mago-expect analysis:invalid-property-assignment-value
}

function test_mixed_and_untyped_parameters(Events $events): void
{
    $events->onValue[] = function (mixed $_value): void {};
    $events->onValue[] = function ($_value): void {};
    $events->onValue[] = function (): void {};
    $events->onValue[] = [$events, 'handleMixed'];
}

function test_argument_accepts_only_mixed(): void
{
    take_mixed_callback(function (int $_value): void {}); // @mago-expect analysis:invalid-argument
}

/**
 * @return callable(mixed): void
 */
function test_return_accepts_only_mixed(): callable
{
    return function (int $_value): void {}; // @mago-expect analysis:invalid-return-statement
}

function test_callable_without_signature_accepts_any(Events $events): void
{
    $events->onAnything[] = function (int $_value): void {};
    $events->onAnyClosure[] = function (int $_value): void {};
}

function test_array_key_parameter_is_strict(Events $events): void
{
    $events->onKey[] = function (int|string $_key): void {};
    $events->onKey[] = function (int $_key): void {}; // @mago-expect analysis:invalid-property-assignment-value
    $events->onKey[] = function (string $_key): void {}; // @mago-expect analysis:invalid-property-assignment-value
    $events->onKey[] = function (bool $_key): void {}; // @mago-expect analysis:invalid-property-assignment-value
}

/**
 * @param array<mixed> $values
 *
 * @return array<mixed>
 */
function test_key_callback_rejects_a_narrower_key(array $values): array
{
    return array_filter($values, fn (int $key): bool => $key > 1, ARRAY_FILTER_USE_KEY); // @mago-expect analysis:invalid-argument
}

function test_int_or_string_parameter_is_strict(Events $events): void
{
    $events->onIntOrString[] = function (int $_key): void {}; // @mago-expect analysis:invalid-property-assignment-value
}

function test_void_return_accepts_void_null_and_never(Events $events): void
{
    $events->onInt[] = function (int $_value): void {};
    $events->onInt[] = fn (int $_value) => null;
    $events->onInt[] = function (int $_value): never {
        exit(1);
    };
}

function test_void_return_rejects_a_value(Events $events): void
{
    $events->onInt[] = function (int $value): int { // @mago-expect analysis:invalid-property-assignment-value
        return $value;
    };
}
