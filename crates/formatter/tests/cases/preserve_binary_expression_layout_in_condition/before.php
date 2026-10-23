<?php

if ($this->model->getConnections()
        ->where([
            'active' => true,
        ])
        ->count('*') > 0) {
    return;
}

if ($this->repository->findBy([
    'active' => true,
])->count('*') === 0) {
    return;
}
