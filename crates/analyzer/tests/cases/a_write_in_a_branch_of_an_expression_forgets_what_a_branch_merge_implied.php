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

function find_member(int $id): null|Member
{
    return $id > 0 ? new Member() : null;
}

function set_flag(bool &$flag): bool
{
    $flag = next_flag();

    return $flag;
}

function write_in_a_match_arm(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $value = match ($kind) {
        1 => $created = next_flag(),
        default => false,
    };
    echo $value ? 1 : 0;

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function by_reference_argument_in_a_match_arm(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $value = match ($kind) {
        1 => set_flag($created),
        default => false,
    };
    echo $value ? 1 : 0;

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function other_variable_written_in_a_match_arm(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $other = false;
    $value = match ($kind) {
        1 => $other = next_flag(),
        default => false,
    };
    echo $value ? 1 : 0, $other ? 1 : 0;

    if ($created) {
        $member->delete();
    }
}

function write_in_a_ternary_branch(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    echo $kind === 1 ? ($created = next_flag()) : false;

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function other_variable_written_in_a_ternary_branch(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $other = false;
    echo $kind === 1 ? ($other = next_flag()) : false;
    echo $other ? 1 : 0;

    if ($created) {
        $member->delete();
    }
}

function write_on_the_right_of_elvis(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    echo $kind ?: ($created = next_flag());

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function write_on_the_right_of_and(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $kind === 1 && ($created = next_flag());

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function by_reference_argument_on_the_right_of_and(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $kind === 1 && set_flag($created);

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function write_on_the_right_of_or(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $kind === 1 || ($created = next_flag());

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function write_on_the_right_of_and_in_an_if_condition(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    if ($kind === 1 && ($created = next_flag())) {
        echo 1;
    }

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function write_on_the_right_of_and_in_an_if_condition_used_in_the_else(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    if ($kind === 1 && ($created = next_flag())) {
        echo 1;
    } else {
        if ($created) {
            $member->delete(); // @mago-expect analysis:possible-method-access-on-null
        }
    }
}

function write_on_the_right_of_and_in_an_if_condition_used_in_the_body(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    if ($kind === 1 && ($created = next_flag())) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function write_on_the_right_of_or_in_an_elseif_condition(bool $create, int $kind): void
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
    } elseif ($kind === 1 || ($created = next_flag())) {
        echo 1;
    }

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function write_in_a_ternary_branch_inside_an_if(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    if ($kind > 0) {
        echo $kind === 1 ? ($created = next_flag()) : false;
    }

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function write_in_a_match_arm_inside_an_if(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    if ($kind > 0) {
        $value = match ($kind) {
            1 => $created = next_flag(),
            default => false,
        };
        echo $value ? 1 : 0;
    }

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function boolean_assignment_on_the_left_of_and_in_an_if_condition_keeps_its_clause(int $kind): void
{
    if (($found_it = ($found = find_member($kind)) !== null) && $kind === 1) {
        echo 1;
    }

    if ($found_it) {
        $found->delete();
    }
}

function variable_assigned_on_the_right_of_and_is_narrowed_in_the_body(int $kind): void
{
    if ($kind === 1 && ($found = find_member($kind)) !== null) {
        $found->delete();
    }
}

function write_in_a_match_arm_that_always_throws(bool $create, int $kind): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $value = match ($kind) {
        1 => throw new RuntimeException(($created = next_flag()) ? 'a' : 'b'),
        default => $kind,
    };
    echo $value;

    if ($created) {
        $member->delete();
    }
}
