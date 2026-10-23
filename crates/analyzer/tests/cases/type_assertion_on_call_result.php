<?php

declare(strict_types=1);

namespace TypeAssertionOnCallResult;

/**
 * @mago-expect analysis:redundant-type-comparison
 */
function always_int(string $value): bool
{
    return is_int(intval($value));
}

/**
 * @mago-expect analysis:impossible-type-comparison
 */
function never_string(string $value): bool
{
    return is_string(intval($value));
}

function narrowed_by_earlier_condition(string $value): bool
{
    if (intval($value)) {
        return is_int(intval($value));
    }

    return false;
}

function possibly_string(mixed $value): bool
{
    return is_string(json_encode($value));
}

function narrowed_by_earlier_spaced_condition(string $value): bool
{
    if (intval( $value )) {
        return is_int(intval($value));
    }

    return false;
}

function narrowed_by_earlier_qualified_condition(string $value): bool
{
    if (\intval($value)) {
        return is_int(\intval($value));
    }

    return false;
}

/**
 * @mago-expect analysis:redundant-type-comparison
 */
function narrowed_qualified_call_in_namespace(string $value): bool
{
    if (\intval($value)) {
        return is_int(intval($value));
    }

    return false;
}

/**
 * @mago-expect analysis:redundant-type-comparison
 */
function narrowed_call_of_another_argument(string $value, string $other): bool
{
    if (intval($other)) {
        return is_int(intval($value));
    }

    return false;
}

function narrowed_by_earlier_named_condition(string $value): bool
{
    if (intval(value: $value, base: 10)) {
        return is_int(intval(value: $value, base: 10));
    }

    return false;
}

/**
 * @mago-expect analysis:redundant-type-comparison
 */
function narrowed_named_call_of_another_spelling(string $value): bool
{
    if (intval(value: $value)) {
        return is_int(intval($value));
    }

    return false;
}

/**
 * @mago-expect analysis:redundant-type-comparison
 */
function narrowed_call_argument_reassigned(string $value): bool
{
    if (intval($value)) {
        $value = 'x';

        return is_int(intval($value));
    }

    return false;
}

/**
 * @mago-expect analysis:redundant-type-comparison
 */
function narrowed_call_argument_reassigned_in_branch(string $value, bool $flag): bool
{
    if (intval($value)) {
        if ($flag) {
            $value = 'x';
        }

        return is_int(intval($value));
    }

    return false;
}

/**
 * @mago-expect analysis:redundant-type-comparison
 */
function narrowed_call_argument_reassigned_in_case(string $value, int $kind): bool
{
    if (intval($value)) {
        switch ($kind) {
            case 1:
                $value = 'x';
                break;
        }

        return is_int(intval($value));
    }

    return false;
}

/**
 * @mago-expect analysis:redundant-type-comparison
 */
function narrowed_call_argument_reassigned_in_ternary(string $value, bool $flag): bool
{
    if (intval($value)) {
        $flag ? $value = 'x' : null;

        return is_int(intval($value));
    }

    return false;
}

final class Row
{
    public string $value = '';
}

/**
 * @mago-expect analysis:redundant-type-comparison
 */
function narrowed_call_property_reassigned(Row $row): bool
{
    if (intval($row->value)) {
        $row->value = 'x';

        return is_int(intval($row->value));
    }

    return false;
}

function narrowed_call_argument_reassigned_in_leaving_branch(string $value, bool $flag): bool
{
    if (intval($value)) {
        if ($flag) {
            $value = 'x';

            return false;
        }

        return is_int(intval($value));
    }

    return false;
}

/**
 * @mago-expect analysis:redundant-type-comparison
 */
function narrowed_in_sibling_branch(string $value, bool $flag): bool
{
    if ($flag) {
        if (intval($value)) {
            echo 1;
        }

        return false;
    }

    return is_int(intval($value));
}

function narrowed_before_both_branches_end(string $value): bool
{
    if (intval($value)) {
        echo 1;
    }

    return is_int(intval($value));
}

function narrowed_by_earlier_condition_with_constant_argument(string $value): bool
{
    if (str_pad($value, 3, ' ', STR_PAD_LEFT)) {
        return is_string(str_pad($value, 3, ' ', STR_PAD_LEFT));
    }

    return false;
}

function narrowed_by_earlier_condition_with_negative_argument(string $value): bool
{
    if (substr($value, -1)) {
        return is_string(substr($value, -1));
    }

    return false;
}

function narrowed_by_earlier_condition_with_binary_argument(string $value, int $offset): bool
{
    if (substr($value, $offset + 1)) {
        return is_string(substr($value, $offset + 1));
    }

    return false;
}

function narrowed_by_earlier_condition_with_array_argument(string $value): bool
{
    if (implode(',', [$value])) {
        return is_string(implode(',', [$value]));
    }

    return false;
}

/**
 * @mago-expect analysis:redundant-type-comparison
 */
function narrowed_call_with_other_negative_argument(string $value): bool
{
    if (substr($value, -1)) {
        return is_string(substr($value, -2));
    }

    return false;
}

/**
 * @mago-expect analysis:redundant-type-comparison
 */
function narrowed_call_binary_argument_reassigned(string $value, int $offset): bool
{
    if (substr($value, $offset + 1)) {
        $offset = 2;

        return is_string(substr($value, $offset + 1));
    }

    return false;
}

/**
 * @param array{key: string} $row
 *
 * @mago-expect analysis:redundant-type-comparison
 */
function narrowed_call_element_reassigned(array $row): bool
{
    if (intval($row['key'])) {
        $row['key'] = 'x';

        return is_int(intval($row['key']));
    }

    return false;
}

/**
 * @param array{key: string, other?: string} $row
 *
 * @mago-expect analysis:redundant-type-comparison
 */
function narrowed_call_array_element_added(array $row): bool
{
    if (intval($row['key'])) {
        $row['other'] = 'y';

        return is_int(intval($row['key']));
    }

    return false;
}

function narrowed_call_of_literal_spelling_a_variable(string $value): bool
{
    if (intval('$value')) {
        $value = 'x';
        echo $value;

        return is_int(intval('$value'));
    }

    return false;
}
