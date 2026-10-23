<?php

function statements(bool $c, array $errors): void
{
    if ($c) {
        validate($errors);

        /** @var list<string> $errors */ //phpcs:ignore
    }

    take(); /* one */ // two
    take(); /* one */ /* two */
    take(); // one /* not a comment */

    /** @var int $e */ // why
    take($e);

    if ($c) {
        /** @var int $e */ /* why */
    }

    switch ($c) {
        case true:
            take();
            /* one */ // two
        default:
            take();
    }

    /* one */ // two
}

function signature() /* one */ // two
{
    return 1;
}

function signatureWithParameters(int $a) /* one */ // two
{
    return $a;
}

function parameters( /* one */ /* two */ ) {}

take(
    /* one */ // two
);

$empty = [
    /* one */ // two
];

$closure = function () {
    /* one */ // two
};

final class Members
{
    public $a; /* one */ // two

    /* one */ /* two */
}

interface Contract
{
    /** one */ /** two */
}

/* one */ // two

/* three */
