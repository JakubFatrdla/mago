<?php

readonly final class Money
{
    public function __construct(private int $amount) {}
}

abstract readonly class Shape
{
    static public int $count = 0;

    public abstract function area(): float;
}

$money = new readonly class(1) {
    public function __construct(public int $amount) {}
};
