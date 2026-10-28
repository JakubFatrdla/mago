<?php

declare(strict_types=1);

final class Member
{
    public function delete(): void
    {
    }
}

function next_flag(): bool
{
    return true;
}

function set_flag(bool &$flag): void
{
    $flag = next_flag();
}

/**
 * @param list<int> $rows
 */
function by_reference_argument_in_a_nested_loop(bool $create, array $rows): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    while (next_flag()) {
        foreach ($rows as $row) {
            echo $row;
            set_flag($created);
        }
    }

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

/**
 * @param list<int> $rows
 */
function by_reference_argument_in_a_switch_case_in_a_loop(bool $create, array $rows): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    foreach ($rows as $row) {
        switch ($row) {
            case 1:
                set_flag($created);
                break;
            default:
                echo $row;
        }
    }

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

/**
 * @param list<bool> $flags
 */
function by_reference_target_in_a_nested_loop(bool $create, array $flags): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    while (next_flag()) {
        foreach ($flags as &$created) {
            echo 1;
        }
    }

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

/**
 * @param list<bool> $flags
 */
function by_reference_target_of_another_variable_in_a_nested_loop(bool $create, array $flags): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    while (next_flag()) {
        foreach ($flags as &$flag) {
            $flag = !$flag;
        }
    }

    if ($created) {
        $member->delete();
    }
}

/**
 * @param list<int> $rows
 */
function by_reference_argument_of_another_variable_in_a_switch_case_in_a_loop(bool $create, array $rows): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $other = false;
    foreach ($rows as $row) {
        switch ($row) {
            case 1:
                set_flag($other);
                break;
            default:
                echo $created ? $row : 0;
        }
    }

    if ($created) {
        $member->delete();
    }
}

/**
 * @param list<int> $rows
 */
function nested_loops_only_read_the_guard(bool $create, array $rows): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    while (next_flag()) {
        foreach ($rows as $row) {
            echo $created ? $row : 0;
        }
    }

    if ($created) {
        $member->delete();
    }
}
