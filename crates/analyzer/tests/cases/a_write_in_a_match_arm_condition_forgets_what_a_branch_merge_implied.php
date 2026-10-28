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

function set_flag(bool &$flag): bool
{
    $flag = next_flag();

    return $flag;
}

function write_in_a_later_match_arm_condition(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $value = match (true) {
        $kind === 1 => 1,
        $created = next_flag() => 2,
        default => 3,
    };
    echo $value;

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function write_in_the_first_match_arm_condition(bool $create): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $value = match (true) {
        $created = next_flag() => 1,
        default => 2,
    };
    echo $value;

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function by_reference_argument_in_the_second_condition_of_a_match_arm(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $value = match (true) {
        $kind === 1, set_flag($created) => 1,
        default => 2,
    };
    echo $value;

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function write_through_a_reference_in_a_later_match_arm_condition(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $alias = &$created;
    $value = match (true) {
        $kind === 1 => 1,
        $alias = next_flag() => 2,
        default => 3,
    };
    unset($alias);
    echo $value;

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function other_variable_written_in_a_later_match_arm_condition(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $other = false;
    $value = match (true) {
        $kind === 1 => 1,
        $other = next_flag() => 2,
        default => 3,
    };
    echo $value, $other ? 1 : 0;

    if ($created) {
        $member->delete();
    }
}
