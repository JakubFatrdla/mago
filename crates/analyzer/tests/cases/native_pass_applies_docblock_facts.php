<?php

declare(strict_types=1);

namespace Fixture;

// With docblock types uncertain, a condition is narrowed and a flow ends the way
// the docblock types decide, as PHPStan's native scope takes both from the PHPDoc
// scope. A loop condition is narrowed by the native types alone.
final class Responder
{
    public function send(): never
    {
        exit(0);
    }
}

/**
 * @method Responder responder()
 */
final class Host
{
    /** @param array<array-key, mixed> $arguments */
    public function __call(string $name, array $arguments): mixed
    {
        return null;
    }

    public function sendOthers(string $extension): bool
    {
        switch ($extension) {
            case 'pdf':
                break;
            case 'png':
                break;
            default:
                $this->responder()->send();
        }

        // @mago-expect analysis:redundant-type-comparison
        return in_array($extension, ['pdf', 'png'], true);
    }

    /** @param list<int> $ids */
    public function indexBelowCount(array $ids, int $index): bool
    {
        // @mago-expect analysis:redundant-isset-check
        if ($index >= 0 && $index < count($ids) && isset($ids[$index])) {
            return true;
        }

        return false;
    }

    /** @param array<string, int> $rates */
    public function intervals(array $rates): int
    {
        $intervals = [];
        $currentRate = 0;
        foreach ($rates as $rate) {
            if (!isset($interval)) {
                $currentRate = $rate;
                $interval = ['rate' => $rate];
                continue;
            }

            if ($currentRate !== $rate) {
                $intervals[] = $interval;
                $currentRate = $rate;
                $interval = ['rate' => $rate];
            }
        }

        return count($intervals);
    }
}
