<?php

function label($q, $p)
{
    if ($p) {
        return $q && 'a' . $this->x()
            ->y()
            ->z()
            . 'b';
    }

    $r = $q || 'a' . $this->x()
        ->y()
        . 'b';

    foo($q && 'a' . $this->x()
        ->y()
        . 'b');

    return match (true) {
        default => $q && 'a' . $this->x()
            ->y()
            . 'b',
    };
}
