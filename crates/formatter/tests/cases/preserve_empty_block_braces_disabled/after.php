<?php

final class Accounts
{
    public function __construct(private Client $client)
    {
    }

    public function reset(): void
    {
    }
}

$noop = function (): void {};

try {
    run();
} catch (Throwable) {
}
