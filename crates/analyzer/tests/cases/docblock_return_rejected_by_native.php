<?php

declare(strict_types=1);

class Row {}

final class ApartmentRow extends Row
{
    public ?string $name = null;
}

final class ApartmentModel
{
    /**
     * @return Row|bool
     */
    public function find(int $id): ?ApartmentRow // @mago-expect analysis:docblock-type-mismatch
    {
        return $id > 0 ? new ApartmentRow() : null;
    }
}

function apartment_name(ApartmentModel $model): ?string
{
    $row = $model->find(1);
    if (!$row) {
        return null;
    }

    return $row->name;
}
