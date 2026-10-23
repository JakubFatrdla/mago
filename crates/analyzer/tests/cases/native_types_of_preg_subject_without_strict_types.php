<?php

namespace NativeTypesOfPregSubjectWithoutStrictTypes;

/**
 * @param array{date: string} $data
 *
 * @throws \InvalidArgumentException
 */
function date_of(array $data): string
{
    if (preg_match('/^\d{4}$/', $data['date']) !== 1) {
        throw new \InvalidArgumentException('Invalid date.');
    }

    return (string) $data['date'];
}
