<?php

declare(strict_types=1);

final class MixedSelectorModule
{
    /** @var list<int> */
    public array $imageIds = [];

    /** @var list<int> */
    public array $iconIds = [];
}

function mixed_selector_write(MixedSelectorModule $module, mixed $name): void
{
    // @mago-expect analysis:invalid-member-selector,string-member-selector
    $module->$name = [1];
}

function mixed_selector_read(MixedSelectorModule $module, mixed $name): mixed
{
    // @mago-expect analysis:invalid-member-selector,string-member-selector
    return $module->$name;
}

function mixed_selector_on_crate(stdClass $row, mixed $name): mixed
{
    // @mago-expect analysis:invalid-member-selector
    return $row->$name;
}

function mixed_selector_call(MixedSelectorModule $module, mixed $name): mixed
{
    // @mago-expect analysis:invalid-member-selector
    return $module->$name();
}
