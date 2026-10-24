<?php

declare(strict_types=1);

function non_object_property_name_taker(mixed $value): void
{
}

function non_object_write_after_isset(mixed $values): void
{
    foreach (['logo', 'stamp'] as $key) {
        // @mago-expect analysis:mixed-property-access(2)
        if (isset($values->$key)) {
            // @mago-expect analysis:mixed-property-access(2)
            $values->$key = 1;
        }
    }
}

function non_object_write(mixed $values): void
{
    foreach (['logo', 'stamp'] as $key) {
        // @mago-expect analysis:mixed-property-access(2)
        $values->$key = 1;
    }
}

function non_object_read_twice(mixed $values): void
{
    foreach (['logo', 'stamp'] as $key) {
        // @mago-expect analysis:mixed-property-access(2)
        non_object_property_name_taker($values->$key);
        // @mago-expect analysis:mixed-property-access(2)
        non_object_property_name_taker($values->$key);
    }
}

function non_object_read_by_identifier(mixed $values): void
{
    // @mago-expect analysis:mixed-property-access
    non_object_property_name_taker($values->logo);
}
