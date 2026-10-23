<?php

for ($i = $this->first()
    ->value(); $i < 10; $i++) {
    echo $i;
}

for ($i = 0; $i < $this->limit()
    ->value(); $i++) {
    echo $i;
}

for ($i = 0; $i < 10; $i = $this->next($i)
    ->value()) {
    echo $i;
}

function rows($rows)
{
    for ($row = $rows->first()
        ->current(); $row !== null; $row = $rows->next()
            ->current()) {
        echo $row;
    }
}
