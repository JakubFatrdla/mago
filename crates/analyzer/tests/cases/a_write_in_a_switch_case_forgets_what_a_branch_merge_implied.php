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

function direct_write_in_a_case(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    switch ($kind) {
        case 1:
            $created = next_flag();
            break;
        default:
            break;
    }

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function write_through_an_alias_in_a_case(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $alias = &$created;
    switch ($kind) {
        case 1:
            $alias = next_flag();
            break;
    }
    unset($alias);

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function write_in_an_if_inside_a_case(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    switch ($kind) {
        case 1:
            if (next_flag()) {
                $created = next_flag();
            }
            break;
    }

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function write_through_an_alias_in_an_if_inside_a_case(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $alias = &$created;
    switch ($kind) {
        case 1:
            if (next_flag()) {
                $alias = next_flag();
            }
            break;
    }
    unset($alias);

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function write_in_a_case_that_returns(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    switch ($kind) {
        case 1:
            $created = next_flag();
            return;
        default:
            break;
    }

    if ($created) {
        $member->delete();
    }
}

function by_reference_argument_in_a_case(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    switch ($kind) {
        case 1:
            set_flag($created);
            break;
    }

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function write_of_another_variable_in_a_case(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $other = false;
    switch ($kind) {
        case 1:
            $other = next_flag();
            break;
    }
    echo $other ? 1 : 0;

    if ($created) {
        $member->delete();
    }
}

function write_in_a_case_that_falls_through(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    switch ($kind) {
        case 1:
            $created = next_flag();
            // no break
        case 2:
            echo 2;
            break;
    }

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function write_in_a_closure_defined_in_a_case(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    switch ($kind) {
        case 1:
            $reset = static function (): bool {
                $created = next_flag();

                return $created;
            };
            echo $reset() ? 1 : 0;
            break;
    }

    if ($created) {
        $member->delete();
    }
}

/**
 * @throws RuntimeException
 */
function write_in_a_case_that_throws(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    switch ($kind) {
        case 1:
            $created = next_flag();
            throw new RuntimeException('x');
    }

    if ($created) {
        $member->delete();
    }
}

function decrement_in_a_case(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $count = 1;
    } else {
        $member = null;
        $count = 0;
    }

    switch ($kind) {
        case 1:
            $count--;
            break;
    }

    if ($count) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}
