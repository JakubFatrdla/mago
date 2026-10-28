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

function log_flag(bool $flag): void
{
    echo $flag ? 'yes' : 'no';
}

function set_flag(bool &$flag): void
{
    $flag = next_flag();
}

function while_body_writes_the_guard(bool $create): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    while (next_flag()) {
        $created = next_flag();
    }

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function do_while_body_writes_the_guard(bool $create): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    do {
        $created = next_flag();
    } while (next_flag());

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function for_body_writes_the_guard(bool $create): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    for ($i = 0; $i < 3; $i++) {
        $created = next_flag();
    }

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

/**
 * @param list<bool> $flags
 */
function foreach_value_target_is_the_guard(bool $create, array $flags): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    foreach ($flags as $created) {
        echo 1;
    }

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

/**
 * @param list<bool> $flags
 */
function nested_branch_in_a_foreach_writes_the_guard(bool $create, array $flags): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    foreach ($flags as $flag) {
        if ($flag) {
            $created = next_flag();
        }
    }

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function by_reference_argument_in_a_loop_writes_the_guard(bool $create): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    while (next_flag()) {
        set_flag($created);
    }

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function nested_loop_writes_the_guard(bool $create): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    while (next_flag()) {
        foreach ([1, 2] as $step) {
            $created = $step > 1;
        }
    }

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function loop_writes_a_flag_set_from_a_null_check(?Member $member): void
{
    if ($member !== null) {
        $found = true;
    } else {
        $found = false;
    }

    while (next_flag()) {
        $found = next_flag();
    }

    if ($found) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function post_increment_moves_the_guard(bool $create): void
{
    if ($create) {
        $member = new Member();
        $level = 1;
    } else {
        $member = null;
        $level = 0;
    }

    $level++;
    if ($level === 1) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function pre_decrement_moves_the_guard(bool $create): void
{
    if ($create) {
        $member = new Member();
        $level = 1;
    } else {
        $member = null;
        $level = 2;
    }

    --$level;
    if ($level === 1) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function loop_writing_another_variable_keeps_the_link(bool $create): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $attempts = 0;
    while (next_flag()) {
        $attempts = 1;
    }

    if ($created) {
        $member->delete();
    }

    echo $attempts;
}

function loop_reading_the_guard_keeps_the_link(bool $create): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    while (next_flag()) {
        log_flag($created);
    }

    if ($created) {
        $member->delete();
    }
}

function increment_of_another_variable_keeps_the_link(bool $create): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $count = 0;
    $count++;
    if ($created) {
        $member->delete();
    }

    echo $count;
}
