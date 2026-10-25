<?php

declare(strict_types=1);

namespace NullNamedBesideOtherNonObjectParts;

final class Holder
{
    public int|string|null $value = null;
}

function year(int|float|null $date): mixed
{
    return $date->format('Y'); // @mago-expect analysis:invalid-method-access
}

function yearOrNull(?\DateTimeImmutable $date): string
{
    return $date->format('Y'); // @mago-expect analysis:possible-method-access-on-null
}

function size(?int $value): mixed
{
    return $value->size; // @mago-expect analysis:invalid-property-access
}

function nestedSize(Holder $holder): mixed
{
    return $holder->value->size; // @mago-expect analysis:invalid-property-access
}

function rememberedSize(Holder $holder): mixed
{
    if ($holder->value !== 0) {
        return $holder->value->size; // @mago-expect analysis:invalid-property-access
    }

    return null;
}

function sizeReadTwice(?int $value): mixed
{
    $first = $value->size; // @mago-expect analysis:invalid-property-access

    return [$first, $value->size]; // @mago-expect analysis:invalid-property-access
}

function objectSize(?\stdClass $value): mixed
{
    return $value->size; // @mago-expect analysis:possibly-null-property-access
}
