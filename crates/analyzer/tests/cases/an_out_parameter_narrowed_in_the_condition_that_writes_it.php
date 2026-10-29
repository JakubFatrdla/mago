<?php

declare(strict_types=1);

function exited_cleanly(int $pid): bool
{
    return pcntl_waitpid($pid, $status) === $pid // @mago-expect analysis:reference-to-undefined-variable,mixed-assignment
        && is_int($status)
        && pcntl_wifexited($status)
        && pcntl_wexitstatus($status) === 0;
}

function exited_cleanly_in_own_statement(int $pid): bool
{
    pcntl_waitpid($pid, $status); // @mago-expect analysis:reference-to-undefined-variable,mixed-assignment

    return is_int($status) && pcntl_wifexited($status) && pcntl_wexitstatus($status) === 0;
}
