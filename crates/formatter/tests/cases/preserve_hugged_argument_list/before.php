<?php

$types = array_filter([
    'House' => $filter['house'] ?? null,
    'Room' => $filter['room'] ?? null,
], static fn (mixed $v): bool => $v !== null);

$value = $cache->load($key, function () use ($loader): array {
    return $loader->load();
}, [Cache::Expire => '5 minutes']);

Assert::same([
    ['country' => 'Austria', 'rate' => '20%'],
], $rows, 'Regions are left out');

$params = $this->getApiParameters(Expect::structure([
    'reason' => Expect::string()->nullable(),
])->otherItems());

$broken = array_filter(
    [1, 2],
    static fn (int $v): bool => $v > 1,
);

$short = sprintf('%s and %s', $first, $second);

return $this->add([
        'contract_id' => $contract->id,
        'template' => 'confirmation',
    ]);

$module = $this->generateModule('all',
    'lastRented',
    3, $type);

return sprintf('Request %d was rejected %s',
    $request->id,
    $context,
);

$modules = [
    'content' => '<h1>Welcome</h1>
<p>Rent a ' . $this->pick([
        'flat',
        'apartment',
    ]) . ' today.</p>',
];
