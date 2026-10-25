<?php

declare(strict_types=1);

class UndeclaredSubtypeHost
{
    public string $type = '';

    public function kind(): int
    {
        if ($this->type === 'rent' || isset($this->subtype) && $this->subtype === 'rent') { // @mago-expect analysis:non-existent-property
            return 1;
        }

        if ($this->type === 'deposit' || isset($this->subtype) && $this->subtype === 'deposit') { // @mago-expect analysis:non-existent-property
            return 2;
        }

        return 0;
    }

    public function narrowedReadStaysSilent(): mixed
    {
        if ($this->other !== null) { // @mago-expect analysis:non-existent-property
            return $this->other;
        }

        return null;
    }

    public function writtenMixedReadStaysSilent(mixed $value): array
    {
        $before = $this->written; // @mago-expect analysis:non-existent-property,mixed-assignment
        $this->written = $value; // @mago-expect analysis:non-existent-property

        return [$before, $this->written];
    }
}

interface UndeclaredIdentity {}

final class UndeclaredUser
{
    public ?UndeclaredIdentity $identity = null;
}

function undeclared_on_nullable_receiver(UndeclaredUser $user, ?string $currency): mixed
{
    if (!$user->identity->currency && $currency !== null) { // @mago-expect analysis:possibly-null-property-access
        echo $currency;
    }

    return $user->identity->currency; // @mago-expect analysis:possibly-null-property-access
}
