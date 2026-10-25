<?php

declare(strict_types=1);

namespace RedundantCheckOnConstructorInitializedProperty;

class Base
{
    protected int $inherited;

    public function __construct(int $value)
    {
        $this->inherited = $value;
    }
}

final class Form extends Base
{
    private int $assigned;
    private int $beforeAssignment;
    private ?int $nullable;
    // @mago-expect analysis:uninitialized-property
    private int $branch;
    private float $maxCommission;
    private int $viaMethod;
    private int $inClosure;

    public function __construct(
        int $value,
        bool $flag,
        private int $promoted,
        private readonly int $readonly,
    ) {
        parent::__construct($value);
        // @mago-expect analysis:uninitialized-property
        $this->beforeAssignment = $this->assigned ?? 0;
        $this->assigned = $value;
        $this->nullable = $flag ? $value : null;
        $this->maxCommission = 1.0;
        if ($flag) {
            $this->branch = $value;
        }
        $this->init($value);
        $this->inClosure = $value;
    }

    private function init(int $value): void
    {
        $this->viaMethod = $value;
    }

    /**
     * @return list<int|float|bool|null>
     */
    public function read(): array
    {
        return [
            $this->beforeAssignment,
            // @mago-expect analysis:redundant-null-coalesce
            $this->assigned ?? null,
            $this->nullable ?? null,
            $this->branch ?? null,
            // @mago-expect analysis:redundant-null-coalesce
            $this->promoted ?? null,
            // @mago-expect analysis:redundant-null-coalesce
            $this->readonly ?? null,
            // @mago-expect analysis:redundant-null-coalesce
            $this->maxCommission ?? 100.0,
            // @mago-expect analysis:redundant-null-coalesce
            $this->viaMethod ?? null,
            $this->inherited ?? null,
            // @mago-expect analysis:redundant-isset-check
            isset($this->assigned),
            isset($this->branch),
        ];
    }

    /**
     * @return list<int|null>
     */
    public function scopes(): array
    {
        $closure = function (): ?int {
            return $this->inClosure ?? null;
        };
        // @mago-expect analysis:redundant-null-coalesce
        $arrow = fn(): ?int => $this->inClosure ?? null;

        return [$closure(), $arrow()];
    }
}
