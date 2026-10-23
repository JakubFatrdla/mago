<?php

declare(strict_types=1);

namespace InstanceofSubclassOfExcludedClass;

class ParentException extends \Exception
{
}

class ChildException extends ParentException
{
}

class OtherException extends \Exception
{
}

/**
 * @mago-expect analysis:impossible-type-comparison
 */
function elseif_after_parent(\Throwable $e): int
{
    if ($e instanceof ParentException) {
        return 1;
    } elseif ($e instanceof ChildException) {
        return 2;
    }

    return 3;
}

/**
 * @mago-expect analysis:impossible-type-comparison
 */
function if_after_returning_parent(\Throwable $e): int
{
    if ($e instanceof ParentException) {
        return 1;
    }

    if ($e instanceof ChildException) {
        return 2;
    }

    if ($e instanceof OtherException) {
        return 4;
    }

    return 3;
}

function unrelated_classes_stay_possible(\Throwable $e): int
{
    if ($e instanceof OtherException) {
        return 1;
    }

    if ($e instanceof ChildException) {
        return 2;
    }

    return 3;
}
