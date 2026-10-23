<?php

readonly final class Money
{
    public function __construct(private int $amount) {}
}

abstract readonly class Shape
{
    public static int $count = 0;

    abstract public function area(): float;
}

$money = new readonly class(1) {
    public function __construct(public int $amount) {}
};
