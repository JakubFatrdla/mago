<?php

declare(strict_types=1);

final class Member
{
    public function delete(): void
    {
    }
}

/**
 * @return list<string>|false
 */
function next_row(): array|false
{
    return false;
}

function maybe_member(): ?Member
{
    return null;
}

function flag_set_with_the_member(bool $create): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    if ($created) {
        if ($member !== null) { // @mago-expect analysis:redundant-comparison
            $member->delete();
        }
    }
}

function closure_use_keeps_the_link(bool $create): Closure
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    return function () use ($created, $member): void {
        if ($created && $member !== null) { // @mago-expect analysis:redundant-comparison
            $member->delete();
        }
    };
}

function arrow_function_keeps_the_link(bool $create): Closure
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    return fn (): bool => $created && $member !== null; // @mago-expect analysis:redundant-comparison
}

function rows_read_by_a_loop_that_ran(): mixed
{
    $rows = [];
    while ($row = next_row()) {
        $rows[] = $row;
    }

    if ($rows === []) {
        return true;
    }

    return $row;
}

function rows_read_under_a_condition(bool $open): mixed
{
    $rows = [];
    if ($open) {
        while ($row = next_row()) {
            $rows[] = $row;
        }
    }

    if (count($rows) < 2) {
        return true;
    }

    return $row;
}

function reassigned_target_forgets_the_link(bool $create): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $member = maybe_member();
    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

/**
 * @param list<array{from: Member|null}> $rows
 */
function each_iteration_reads_a_new_value(array $rows, int $nights): void
{
    foreach ($rows as $row) {
        $from = $row['from'];
        if ($nights > 0) {
            $from->delete(); // @mago-expect analysis:possible-method-access-on-null
            $from = new Member();
        }
    }
}

function nested_guard_types_link_nothing(int $offset): void
{
    $member = null;
    if ($offset === 5) {
        $member = new Member();
        $offset = 1;
    }

    if ($offset === 0) {
        if ($member === null) {
            echo 'unchanged';
        }
    }
}

/**
 * @param list<Member|null> $members
 */
function each_iteration_reads_a_new_element(array $members, int $mode): void
{
    $replaced = false;
    foreach ($members as $member) {
        if ($replaced) {
            $member->delete(); // @mago-expect analysis:possible-method-access-on-null
        }

        if ($mode > 0) {
            $member = new Member();
            $replaced = true;
        } else {
            $replaced = false;
        }
    }
}

function nested_guard_types_leave_a_variable_possibly_undefined(int $offset): void
{
    if ($offset === 5) {
        $offset = 1;
    } else {
        $member = new Member();
    }

    if ($offset === 0) {
        $member->delete(); // @mago-expect analysis:possibly-undefined-variable
    }
}
