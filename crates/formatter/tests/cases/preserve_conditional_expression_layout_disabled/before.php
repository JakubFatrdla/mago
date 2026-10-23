<?php

$template = $row === null || $row->template === ''
    ? 'default'
    : explode('.', $row->template)[0];

$label = $count > 1 ? 'items' : 'item';

$states = $override ? [
    'address' => true,
] : [
    'address' => $ready,
];

function pick(?Row $row): ?string
{
    return isset($row->id) ? $this->repository->find([
        'id' => $row->id,
    ]) : null;
}

$spaced = $flag
            ?   'yes'
            :   'no';
