<?php

$rows = $this->repository->findBy([
    'state' => $state,
])->order('id')->limit(10)->fetchAll();

$query = $this->database->table('reservation')
    ->where('state', $state)->order('id')
    ->fetchAll();

$builder
    ->select('id')
    ->from('offer');

$value = $object?->first()
    ?->second;

$validator->field('price')
    ->when(Rule::Filled)
        ->require(Rule::Number);
