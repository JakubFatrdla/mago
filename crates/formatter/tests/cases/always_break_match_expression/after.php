<?php

function single_arm_broken(Kind $kind): string
{
    return match ($kind) {
        Kind::Draft, Kind::Pending, Kind::Review => 'open',
    };
}

function single_arm_inline(Kind $kind): string
{
    return match ($kind) {
        Kind::Draft => 'draft',
    };
}

function several_arms(Kind $kind): string
{
    return match ($kind) {
        Kind::Draft => 'draft',
        Kind::Published => 'published',
        default => 'other',
    };
}

function empty_match(Kind $kind): never
{
    match ($kind) { };
}
