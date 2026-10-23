<?php

$sum = ceil((float) $this->repository->findBy([
            'active' => true,
        ])
        ->sum('amount'));
