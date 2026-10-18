<?php

declare(strict_types=1);

/** @param ArrayAccess<string, mixed>&Traversable<string, mixed> $values */
function nested_check(ArrayAccess $values): string
{
    if (isset($values['url'])) {
        if (is_string($values['url'])) {
            return $values['url'];
        }
    }

    return '';
}

/** @param ArrayAccess<string, mixed>&Traversable<string, mixed> $values */
function early_exit(ArrayAccess $values): string
{
    if (!isset($values['url'])) {
        return '';
    }

    if (!is_string($values['url'])) {
        return '';
    }

    return $values['url'];
}

/** @param ArrayAccess<string, mixed>&Traversable<string, mixed> $values */
function isset_only(ArrayAccess $values): string
{
    if (isset($values['url'])) {
        /** @mago-expect analysis:mixed-return-statement */
        return $values['url'];
    }

    return '';
}
