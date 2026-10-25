<?php

declare(strict_types=1);

namespace BuiltInFalseReturnsReportedLikePhpstan;

function writeFile(string $path, string $data): void
{
    $file = fopen($path, 'w');
    // @mago-expect analysis:possibly-false-argument
    fwrite($file, $data);
    // @mago-expect analysis:possibly-false-argument
    fclose($file);
}

function writeChecked(string $path, string $data): void
{
    $file = fopen($path, 'w');
    if ($file === false) {
        return;
    }

    fwrite($file, $data);
    fclose($file);
}

/**
 * @param resource|string $data
 */
function read(mixed $data): void
{
}

function readFile(string $path): void
{
    // @mago-expect analysis:possibly-false-argument
    read(fopen($path, 'r'));
}

/**
 * @mago-expect analysis:falsable-return-statement,invalid-return-statement
 */
function createCurl(): \CurlHandle
{
    $curl = curl_init();
    // @mago-expect analysis:possibly-false-argument
    curl_setopt($curl, CURLOPT_POST, true);

    return $curl;
}

function copyCurl(\CurlHandle $curl): void
{
    // @mago-expect analysis:possibly-false-argument
    curl_exec(curl_copy_handle($curl));
}

function scale(\GdImage $image): void
{
    // @mago-expect analysis:possibly-false-argument
    imagesx(imagescale($image, 10));
}

function pattern(\IntlDateFormatter $formatter): string
{
    // @mago-expect analysis:possibly-false-argument
    return trim($formatter->getPattern());
}

/**
 * @return array<mixed>
 */
function words(string $bytes): array
{
    // @mago-expect analysis:possibly-false-argument
    return array_values(unpack('n*', $bytes));
}

function zone(\DateTime $date): \DateTimeZone
{
    return $date->getTimezone();
}

function time(\IntlCalendar $calendar): float
{
    return $calendar->getTime();
}

function calendarZone(\IntlCalendar $calendar): \IntlTimeZone
{
    return $calendar->getTimeZone();
}

function character(): string
{
    return mb_chr(65);
}

function codePoint(): int
{
    return mb_ord('A');
}
