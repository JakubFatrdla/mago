<?php

function defaults($form, $x, $repository, $id)
{
    $form->setDefaults(array_replace([
            'a' => 1,
            'b' => 0,
        ], $x));

    $form->setItems($repository->findBy([
            'id' => $id,
        ])->fetchPairs('id', 'name'));
}
