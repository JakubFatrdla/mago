<?php

declare(strict_types=1);

/** @param array{equipment: array<string, string>|null} $room */
function isset_in_and_after_an_early_return(array $room): void
{
    if (!isset($room['equipment'])) {
        return;
    }

    if (isset($room['equipment']['desk']) && $room['equipment']['desk'] === 'true') {
        echo 1;
    }

    if (isset($room['equipment']['terrace']) && $room['equipment']['terrace'] === 'true') {
        echo 2;
    }
}

/** @param array{equipment: array<string, string>|null} $room */
function isset_in_if_inside_an_isset_of_the_container(array $room): void
{
    if (isset($room['equipment'])) {
        if (isset($room['equipment']['desk'])) {
            echo $room['equipment']['desk'];
        }
    }
}

/** @param array<string, array<string, string>|null> $room */
function isset_in_ternary_after_an_early_return(array $room): string
{
    if (!isset($room['equipment'])) {
        return '';
    }

    return isset($room['equipment']['desk']) ? $room['equipment']['desk'] : '';
}

/** @param list<array{equipment: array<string, string>|null}> $rooms */
function isset_in_a_loop_after_a_continue(array $rooms): void
{
    foreach ($rooms as $room) {
        if (!isset($room['equipment'])) {
            continue;
        }

        if (isset($room['equipment']['desk']) && $room['equipment']['desk'] === 'true') {
            echo 1;
        }
    }
}

/** @param array{equipment: array<string, string>|null} $room */
function read_without_isset_of_the_offset(array $room): void
{
    if (!isset($room['equipment'])) {
        return;
    }

    echo $room['equipment']['desk']; // @mago-expect analysis:possibly-undefined-string-array-index
}
