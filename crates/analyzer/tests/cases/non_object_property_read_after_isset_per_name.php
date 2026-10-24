<?php

declare(strict_types=1);

function non_object_read_after_isset(mixed $values): mixed
{
    foreach (['logo', 'stamp'] as $key) {
        // @mago-expect analysis:mixed-property-access(2)
        if (isset($values->$key)) {
            // @mago-expect analysis:mixed-property-access(2)
            return $values->$key;
        }
    }

    return null;
}

function non_object_unset_after_isset(mixed $values): void
{
    foreach (['logo', 'stamp'] as $key) {
        // @mago-expect analysis:mixed-property-access(2)
        if (isset($values->$key)) {
            // @mago-expect analysis:mixed-property-access(2)
            unset($values->$key);
        }
    }
}

function object_read_after_isset_by_identifier(mixed $values): mixed
{
    // @mago-expect analysis:mixed-property-access
    if (isset($values->logo)) {
        return $values->logo;
    }

    return null;
}
