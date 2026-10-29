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

function guard_written_before_the_link_then_a_ternary_branch_write_in_a_ternary_condition(bool $create, int $kind): void
{
    $created = false;
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

function guard_written_before_the_link_then_a_ternary_condition_without_a_write(bool $create, int $kind): void
{
    $created = false;
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    echo ($kind > 0 && $kind < 5) ? 1 : 0;

    if ($created) {
        $member->delete();
    }
}

function guard_written_before_the_link_then_a_ternary_branch_write_on_the_left_of_coalesce(bool $create, int $kind): void
{
    $created = false;
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $value = ($kind > 0 && ($kind === 1 ? ($created = next_flag()) : false) ? $kind : null) ?? -1;
    echo $value;

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function guard_written_before_the_link_then_a_left_of_coalesce_without_a_write(bool $create, int $kind): void
{
    $created = false;
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $value = ($kind > 0 && $kind < 5 ? $kind : null) ?? -1;
    echo $value;

    if ($created) {
        $member->delete();
    }
}

function guard_written_before_the_link_then_a_ternary_branch_write_in_an_if_condition(bool $create, int $kind): void
{
    $created = false;
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
