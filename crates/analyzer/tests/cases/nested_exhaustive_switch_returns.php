<?php

declare(strict_types=1);

namespace NestedExhaustiveSwitchReturns;

/**
 * @param 'cs'|'en' $lang
 *
 * @throws \InvalidArgumentException
 *
 * @mago-expect analysis:useless-control-flow
 * @mago-expect analysis:unevaluated-code
 */
function literal_subject(string $lang, int $type): string
{
    switch ($type) {
        case 1:
            switch ($lang) {
                case 'cs':
                    return 'a';
                case 'en':
                    return 'b';
            }
            break;
        default:
            throw new \InvalidArgumentException('type');
    }

    return '';
}

/**
 * @throws \InvalidArgumentException
 *
 * @mago-expect analysis:useless-control-flow
 * @mago-expect analysis:unevaluated-code
 */
function subject_narrowed_by_in_array(string $lang, int $type): string
{
    if (!in_array($lang, ['cs', 'en'], true)) {
        throw new \InvalidArgumentException('lang');
    }

    switch ($type) {
        case 1:
            switch ($lang) {
                case 'cs':
                    return 'a';
                case 'en':
                    return 'b';
            }
            break;
        default:
            throw new \InvalidArgumentException('type');
    }

    return '';
}

/**
 * @throws \InvalidArgumentException
 */
function inner_switch_not_exhaustive(string $lang, int $type): string
{
    switch ($type) {
        case 1:
            switch ($lang) {
                case 'cs':
                    return 'a';
                case 'en':
                    return 'b';
            }
            break;
        default:
            throw new \InvalidArgumentException('type');
    }

    return '';
}

/**
 * @throws \InvalidArgumentException
 *
 * @mago-expect analysis:useless-control-flow
 */
function outer_switch_without_default(string $lang, int $type): string
{
    if (!in_array($lang, ['cs', 'en'], true)) {
        throw new \InvalidArgumentException('lang');
    }

    switch ($type) {
        case 1:
            switch ($lang) {
                case 'cs':
                    return 'a';
                case 'en':
                    return 'b';
            }
            break;
    }

    return '';
}
