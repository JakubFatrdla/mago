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

function alias_rebound_by_static_before_a_write(bool $create): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $alias = &$created;
    static $alias = false;
    $alias = next_flag();

    if ($created) {
        $member->delete();
    }
}

function alias_rebound_by_global_before_a_write(bool $create): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $alias = &$created;
    global $alias;
    $alias = next_flag();

    if ($created) {
        $member->delete();
    }
}

function target_rebound_by_global_before_an_alias_write(): void
{
    $member = new Member();
    $alias = &$member;
    global $member;
    $alias = null;
    unset($alias);

    if ($member instanceof Member) {
        $member->delete();
    }
}

function target_rebound_by_static_before_an_alias_write(): void
{
    $member = new Member();
    $alias = &$member;
    static $member = new Member();
    $alias = null;
    unset($alias);

    $member->delete();
}
