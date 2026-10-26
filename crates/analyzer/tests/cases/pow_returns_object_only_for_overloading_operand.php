<?php

declare(strict_types=1);

/**
 * @param numeric-string $numeric
 */
function pow_of_numbers_is_a_number(int $base, int $exponent, float $fraction, string $numeric): float|int
{
    return (1 / pow($base, $exponent)) + pow($fraction, 2) + pow($numeric, $exponent) + pow(true, 2);
}

function pow_of_gmp_is_gmp(GMP $gmp): GMP
{
    return pow($gmp, 2);
}

function pow_to_gmp_is_gmp(GMP $gmp): GMP
{
    return pow(2, $gmp);
}

function pow_of_number_is_number(BcMath\Number $number): BcMath\Number
{
    return pow($number, 2);
}
