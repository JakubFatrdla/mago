<?php

$column = match ($type) {
    Type::Invoice,
    Type::CreditNote => 'invoice_id',
    Type::Payment,   Type::Refund   => 'payment_id',
    Type::Fee,
    Type::Penalty,
    Type::Other => 'fee_id',
    default => null,
};
