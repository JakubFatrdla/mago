<?php

declare(strict_types=1);

final class MemoizedReceiverRow
{
    public mixed $type = null;
}

function memoized_receiver_after_mixed_comparison(mixed $row, mixed $other): bool
{
    return (
        $row->seo->city_id // @mago-expect analysis:mixed-property-access,mixed-property-access,mixed-operand
        && $row->seo->type === $other // @mago-expect analysis:mixed-property-access,mixed-property-access
        && $row->seo->city_id === 1 // @mago-expect analysis:mixed-property-access,mixed-property-access
    );
}

function memoized_receiver_after_magic_comparison(mixed $row, MemoizedReceiverRow $other): bool
{
    return (
        $row->seo->city_id // @mago-expect analysis:mixed-property-access,mixed-property-access,mixed-operand
        && $row->seo->type === $other->type // @mago-expect analysis:mixed-property-access,mixed-property-access
        && $row->seo->city_id === 1 // @mago-expect analysis:mixed-property-access,mixed-property-access
    );
}

function memoized_receiver_in_coalesce(): Closure
{
    return function ($values): string {
        $name = $values->country === 'sk' ? 'vat_sk' : 'vat'; // @mago-expect analysis:mixed-property-access
        $submitted = is_string($values->$name) ? $values->$name : ''; // @mago-expect analysis:mixed-property-access,mixed-property-access,mixed-property-access,mixed-property-access
        $again = $values->$name ?? null; // @mago-expect analysis:mixed-property-access,mixed-property-access,mixed-assignment

        return $submitted . (is_string($again) ? $again : '');
    };
}
