<?php

function label($q, $p)
{
    if ($p) {
        return $q . ($p ? $this->x()
            ->y()
            ->z() : 'c');
    }

    $r = $q . ($p ? 'c' : $this->x()
        ->y());

    foo($q . ($p ? $this->x()
        ->y() : 'c'));

    return match (true) {
        default => $q . ($p ? $this->x()
            ->y() : 'c'),
    };
}
