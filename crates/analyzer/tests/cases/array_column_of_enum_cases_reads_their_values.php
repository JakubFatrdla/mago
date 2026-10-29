<?php

declare(strict_types=1);

enum Group: string
{
    case Newsletter = 'newsletter';
    case Service = 'service';
}

enum Level: int
{
    case Low = 1;
    case High = 2;
}

/**
 * @param list<'newsletter'|'service'> $values
 */
function takes_group_values(array $values): void {}

/**
 * @param list<'Newsletter'|'Service'> $names
 */
function takes_group_names(array $names): void {}

/**
 * @param list<1|2> $values
 */
function takes_level_values(array $values): void {}

/**
 * @return array<string, int>
 */
function flipped_values(): array
{
    return array_flip(['other', ...array_column(Group::cases(), 'value')]);
}

function joined_values(): string
{
    takes_group_values(array_column(Group::cases(), 'value'));
    takes_group_names(array_column(Group::cases(), 'name'));
    takes_level_values(array_column(Level::cases(), 'value'));

    return implode(', ', array_column(Group::cases(), 'value'));
}
