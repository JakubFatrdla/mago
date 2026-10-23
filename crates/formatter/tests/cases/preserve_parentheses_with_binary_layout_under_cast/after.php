<?php

$sum = (float) ($this->contract->reservation
    ?->related('payment')
    ->sum('amount') ?? 0);
