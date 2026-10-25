<?php

declare(strict_types=1);

namespace NestedOffsetWriteChecksDeclaredPropertyType;

final class Settings
{
    /** @var array<string, array<string, int>> */
    public array $groups = [];

    /** @var array{limits?: array{max: int}, flags?: array{on?: bool}} */
    public array $options = [];
}

function test_valid_nested_write_after_invalid_one(Settings $settings): void
{
    // @mago-expect analysis:invalid-property-assignment-value
    $settings->groups['first']['size'] = 'large';
    $settings->groups['second']['size'] = 2;
}

function test_invalid_nested_write_after_invalid_one(Settings $settings): void
{
    // @mago-expect analysis:invalid-property-assignment-value
    $settings->groups['first']['size'] = 'large';
    // @mago-expect analysis:invalid-property-assignment-value
    $settings->groups['first']['count'] = 'many';
}

function test_valid_nested_write_after_invalid_shape_write(Settings $settings): void
{
    // @mago-expect analysis:invalid-property-assignment-value
    $settings->options['limits']['max'] = 'none';
    $settings->options['flags']['on'] = true;
}
