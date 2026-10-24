<?php

declare(strict_types=1);

namespace OffsetWriteChecksDeclaredPropertyType;

final class Events
{
    /** @var array<callable(int): void> */
    public array $handlers = [];

    /** @var array<string, callable(int): void> */
    public array $named = [];

    /** @var array<callable(int): void> */
    public static array $shared = [];

    /** @var list<int>|null */
    public null|array $numbers = null;
}

function test_valid_append_after_invalid_one(Events $events): void
{
    $events->handlers[] = function (string $_value): void {}; // @mago-expect analysis:invalid-property-assignment-value
    $events->handlers[] = function (int $_value): void {};
}

function test_valid_key_write_after_invalid_one(Events $events): void
{
    $events->named['first'] = function (string $_value): void {}; // @mago-expect analysis:invalid-property-assignment-value
    $events->named['second'] = function (int $_value): void {};
}

function test_valid_static_append_after_invalid_one(): void
{
    Events::$shared[] = function (string $_value): void {}; // @mago-expect analysis:invalid-property-assignment-value
    Events::$shared[] = function (int $_value): void {};
}

function test_valid_append_after_invalid_assignment(Events $events): void
{
    $events->handlers = ['handler']; // @mago-expect analysis:invalid-property-assignment-value
    $events->handlers[] = function (int $_value): void {};
}

function test_append_to_nullable_property(Events $events): void
{
    $events->numbers = [];
    $events->numbers[] = 1;
    $events->numbers[] = 'two'; // @mago-expect analysis:invalid-property-assignment-value
    $events->numbers[] = 3;
}
