<?php

final class Command
{
    public function __construct(Clock $clock,
        private readonly Repository $repository,
        private readonly Manager $manager,
    ) {
        parent::__construct($clock);
    }

    public function run(string $name, int $count,
        bool $force = false): void
    {
    }

    public function single(string $name): void {}
}
