<?php

declare(strict_types=1);

namespace LoopConditionAssignmentExitType;

function takes_false(false $value): void
{
}

function takes_null(null $value): void
{
}

/**
 * @param resource $handle
 */
function lines($handle): int
{
    $count = 0;
    $line = '';
    while (($line = fgets($handle)) !== false) {
        $count++;
    }

    takes_false($line);

    return $count;
}

/**
 * @param list<int> $values
 */
function shifted(array $values): int
{
    $sum = 0;
    $value = 0;
    while (($value = array_shift($values)) !== null) {
        $sum += $value;
    }

    takes_null($value);

    return $sum;
}

/**
 * @param resource $handle
 */
function has_marker($handle): int
{
    $line = '';
    while (($line = fgets($handle)) !== false) {
        if ($line === "x\n") {
            break;
        }
    }

    return $line === false ? 0 : 1;
}

/**
 * @param resource $handle
 *
 * @mago-expect analysis:possibly-false-argument
 */
function marker_length($handle): int
{
    $line = '';
    while (($line = fgets($handle)) !== false) {
        if ($line === "x\n") {
            break;
        }
    }

    return strlen($line);
}

/**
 * @param resource $handle
 * @param list<string> $stops
 *
 * @mago-expect analysis:possibly-false-argument
 */
function stop_length($handle, array $stops): int
{
    $line = '';
    while (($line = fgets($handle)) !== false) {
        foreach ($stops as $stop) {
            if ($line === $stop) {
                break 2;
            }
        }
    }

    return strlen($line);
}

/**
 * @param resource $handle
 * @param list<string> $stops
 */
function has_stop($handle, array $stops): int
{
    $line = '';
    while (($line = fgets($handle)) !== false) {
        foreach ($stops as $stop) {
            if ($line === $stop) {
                break 2;
            }
        }
    }

    return $line === false ? 0 : 1;
}

/**
 * @param resource $handle
 *
 * @mago-expect analysis:possibly-false-argument
 */
function case_length($handle): int
{
    $line = '';
    while (($line = fgets($handle)) !== false) {
        switch ($line) {
            case "x\n":
                break 2;
        }
    }

    return strlen($line);
}

/**
 * @param resource $handle
 *
 * @mago-expect analysis:possibly-false-argument
 */
function first_line_length($handle): int
{
    $line = '';
    while (($line = fgets($handle)) !== false) {
        break;
    }

    return strlen($line);
}
