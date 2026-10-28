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

function write_through_an_alias(bool $create): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $alias = &$created;
    $alias = next_flag();
    unset($alias);

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function write_through_an_alias_in_a_branch(bool $create): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $alias = &$created;
    if (next_flag()) {
        $alias = next_flag();
    }
    unset($alias);

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function write_through_an_alias_in_a_loop(bool $create): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $alias = &$created;
    while (next_flag()) {
        $alias = next_flag();
    }
    unset($alias);

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

/**
 * @param list<int> $rows
 */
function write_through_an_alias_in_a_nested_loop(bool $create, array $rows): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $alias = &$created;
    while (next_flag()) {
        foreach ($rows as $row) {
            echo $row;
            $alias = next_flag();
        }
    }
    unset($alias);

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

/**
 * @param list<int> $rows
 */
function write_through_an_alias_in_a_branch_inside_a_loop(bool $create, array $rows): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $alias = &$created;
    foreach ($rows as $row) {
        if ($row > 1) {
            $alias = next_flag();
        }
    }
    unset($alias);

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function write_of_null_through_an_alias_of_the_target(bool $create): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $alias = &$member;
    $alias = null;
    unset($alias);

    if ($created) {
        $member->delete(); // @mago-expect analysis:method-access-on-null
    }
}

function by_reference_argument_through_an_alias(bool $create): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $alias = &$created;
    set_flag($alias);
    unset($alias);

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

/**
 * @param list<bool> $flags
 */
function alias_as_a_foreach_value_target(bool $create, array $flags): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $alias = &$created;
    foreach ($flags as $alias) {
        echo $alias ? 1 : 0;
    }
    unset($alias);

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function alias_never_written(bool $create): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $alias = &$created;
    echo $alias ? 1 : 0;
    unset($alias);

    if ($created) {
        $member->delete();
    }
}

function write_through_an_alias_of_another_variable(bool $create): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $other = false;
    $alias = &$other;
    $alias = next_flag();
    unset($alias);
    echo $other ? 1 : 0;

    if ($created) {
        $member->delete();
    }
}

function alias_unset_before_a_plain_write(bool $create): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $alias = &$created;
    unset($alias);
    $alias = next_flag();
    echo $alias ? 1 : 0;

    if ($created) {
        $member->delete();
    }
}

function alias_rebound_before_the_write(bool $create): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $other = false;
    $alias = &$created;
    $alias = &$other;
    $alias = next_flag();
    unset($alias);
    echo $other ? 1 : 0;

    if ($created) {
        $member->delete();
    }
}

function decrement_through_an_alias(bool $create): void
{
    if ($create) {
        $member = new Member();
        $count = 1;
    } else {
        $member = null;
        $count = 0;
    }

    $alias = &$count;
    $alias--;
    unset($alias);

    if ($count) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function null_written_through_an_alias_without_a_link(): void
{
    $member = new Member();
    $alias = &$member;
    $alias = null;
    unset($alias);
    $member->delete(); // @mago-expect analysis:method-access-on-null
}

function target_rebound_before_an_alias_write(): void
{
    $member = new Member();
    $alias = &$member;
    $other = new Member();
    $member = &$other;
    $alias = null;
    $member->delete();
    unset($alias);
}

final class Holder
{
    public ?Member $member = null;

    public function property_alias_written_with_null(): void
    {
        $this->member = new Member();
        $alias = &$this->member;
        $alias = null;
        unset($alias);
        $this->member->delete();
    }
}
