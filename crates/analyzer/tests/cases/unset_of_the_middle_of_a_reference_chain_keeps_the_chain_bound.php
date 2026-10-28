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

function write_through_the_end_of_a_chain_after_its_middle_is_unset(bool $create): void
{
    if ($create) {
        $member = new Member();
        $created = true;
    } else {
        $member = null;
        $created = false;
    }

    $b = &$created;
    $c = &$b;
    unset($b);
    $c = next_flag();
    unset($c);

    if ($created) {
        $member->delete(); // @mago-expect analysis:possible-method-access-on-null
    }
}

function null_written_through_the_end_of_a_chain_after_its_middle_is_unset(): void
{
    $member = new Member();
    $b = &$member;
    $c = &$b;
    unset($b);
    $c = null;
    unset($c);

    $member->delete(); // @mago-expect analysis:method-access-on-null
}

function null_written_through_the_middle_of_a_chain_after_its_end_is_unset(): void
{
    $member = new Member();
    $b = &$member;
    $c = &$b;
    unset($c);
    $b = null;
    unset($b);

    $member->delete(); // @mago-expect analysis:method-access-on-null
}
