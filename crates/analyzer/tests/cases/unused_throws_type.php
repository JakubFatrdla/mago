<?php

declare(strict_types=1);

namespace UnusedThrowsType;

class DomainFailure extends \RuntimeException
{
}

final class Service
{
    /**
     * @throws \RuntimeException
     *
     * @mago-expect analysis:unused-throws-type
     */
    public function nothingThrown(): void
    {
        echo 1;
    }

    /**
     * @throws \RuntimeException
     */
    public function subclassThrown(): void
    {
        throw new DomainFailure('x');
    }

    /**
     * @throws \RuntimeException
     */
    public function implicitThrow(): void
    {
        $this->helper();
    }

    /**
     * @throws \RuntimeException|\LogicException
     *
     * @mago-expect analysis:unused-throws-type
     */
    public function oneOfTwoThrown(): void
    {
        throw new \LogicException('x');
    }

    /**
     * @throws \LogicException
     *
     * @mago-expect analysis:unused-throws-type
     */
    public function caughtInside(): void
    {
        try {
            throw new \LogicException('x');
        } catch (\LogicException) {
            echo 1;
        }
    }

    /**
     * @throws void
     */
    public function throwsNothing(): void
    {
        echo 2;
    }

    private function helper(): void
    {
        echo 3;
    }
}

class Extensible
{
    /**
     * @throws \RuntimeException
     */
    public function mayBeOverridden(): void
    {
        echo 1;
    }
}

/**
 * @throws \RuntimeException
 *
 * @mago-expect analysis:unused-throws-type
 */
function function_nothing_thrown(): void
{
    echo 1;
}
