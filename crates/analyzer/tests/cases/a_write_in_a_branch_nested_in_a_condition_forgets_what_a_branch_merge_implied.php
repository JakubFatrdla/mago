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

function ternary_branch_write_on_the_right_of_and_in_an_if_condition(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    if ($kind > 0 && ($kind === 1 ? ($created = next_flag()) : false)) {
        echo 1;
    }

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function ternary_branch_write_on_the_right_of_and_in_an_elseif_condition(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    if ($kind > 5) {
        echo 5;
    } elseif ($kind > 0 && ($kind === 1 ? ($created = next_flag()) : false)) {
        echo 1;
    }

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function ternary_branch_write_on_the_right_of_and_in_a_ternary_condition(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    echo ($kind > 0 && ($kind === 1 ? ($created = next_flag()) : false)) ? 1 : 0;

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function by_reference_argument_in_a_ternary_branch_on_the_right_of_and_in_an_if_condition(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    if ($kind > 0 && ($kind === 1 ? set_flag($created) : false)) {
        echo 1;
    }

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function match_arm_write_on_the_right_of_or_in_an_if_condition(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    if ($kind > 0 || match ($kind) {
        -1 => $created = next_flag(),
        default => false,
    }) {
        echo 1;
    }

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function other_variable_written_in_a_ternary_branch_on_the_right_of_and_in_an_if_condition(
    bool $create,
    int $kind,
): void {
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $other = false;
    if ($kind > 0 && ($kind === 1 ? ($other = next_flag()) : false)) {
        echo 1;
    }
    echo (int) $other;

    if ($created) {
        $member->delete();
    }
}
