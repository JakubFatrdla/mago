<?php

final class Example
{
    use FirstTrait;
    use SecondTrait;

    public const A = 1;
    public const B = 2;

    public const C = 3;

    private int $first = 1;
    private int $second = 2;

    private int $third = 3;

    public function first(): void {}

    public function second(): void {}

    /**
     * Docblock.
     */
    public function third(): void {}

    public function fourth(): void {}
}

enum Suit
{
    case Hearts;
    case Spades;

    public function color(): string
    {
        return 'Red';
    }
}

interface Shape
{
    public function area(): float;

    public function perimeter(): float;
}
