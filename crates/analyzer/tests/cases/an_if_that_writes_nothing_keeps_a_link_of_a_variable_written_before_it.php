<?php

declare(strict_types=1);

final class Member
{
    public function delete(): void
    {
    }
}

final class Guest
{
}

function find_member(int $kind): ?Member
{
    return $kind > 2 ? new Member() : null;
}

function take_member(Member $member): void
{
    echo $member::class;
}

function next_flag(): bool
{
    return true;
}

function pair_nulled_together_then_an_if_that_writes_nothing(int $kind): string
{
    $from = new DateTimeImmutable();
    $to = new DateTimeImmutable();
    if ($kind > 3) {
        $from = null;
        $to = null;
    }

    if ($kind === 9) {
        return 'nine';
    }

    return $from !== null ? $to->format('Y') : '';
}

function flag_set_with_a_fallback_then_an_if_that_writes_nothing(int $kind): void
{
    $useGuest = false;
    $row = find_member($kind);
    if ($row === null) {
        $row = new Guest();
        $useGuest = true;
    }

    if ($kind === 9) {
        echo 9;
    } elseif ($kind === 8) {
        echo 8;
    } else {
        echo 0;
    }

    if ($useGuest) {
        return;
    }

    take_member($row);
}

function guard_written_before_the_link_then_an_elseif_that_writes_nothing(bool $create, int $kind): void
{
    $created = false;
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    if ($kind > 5) {
        echo 5;
    } elseif ($kind > 0) {
        echo 1;
    }

    if ($created) {
        $member->delete();
    }
}

function guard_written_before_the_link_then_a_ternary_branch_write_in_an_elseif_condition(bool $create, int $kind): void
{
    $created = false;
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

function guard_written_before_the_link_then_a_write_in_an_if_body(bool $create, int $kind): void
{
    $created = false;
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    if ($kind > 5) {
        $created = next_flag();
    }

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function write_in_an_if_body_then_a_nested_if(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    if ($kind > 0) {
        $created = next_flag();
        if ($kind > 5) {
            echo 5;
        }
    }

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function write_in_an_else_body_then_a_nested_if(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    if ($kind > 0) {
        echo 0;
    } else {
        $created = next_flag();
        if ($kind < -5) {
            echo 5;
        }
    }

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}
