<?php

declare(strict_types=1);

namespace MagicPropertyKeepsRejectedWrite;

/**
 * @property string $agentId
 */
final class Setting
{
    public function __get(string $name): mixed
    {
        return null;
    }

    public function __set(string $name, mixed $value): void
    {
        echo $name;
    }
}

function visit(string $id): void
{
    echo $id;
}

function mixed_write(Setting $setting, mixed $affiliate): void
{
    // @mago-expect analysis:mixed-property-type-coercion
    $setting->agentId = $affiliate;
    // @mago-expect analysis:mixed-argument
    visit($setting->agentId);
}

function int_write(Setting $setting, int $id): void
{
    // @mago-expect analysis:invalid-property-assignment-value
    $setting->agentId = $id;
    // @mago-expect analysis:invalid-argument
    visit($setting->agentId);
}

function string_write(Setting $setting, string $id): void
{
    $setting->agentId = $id;
    visit($setting->agentId);
}
