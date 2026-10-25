<?php

declare(strict_types=1);

final class VariableMethodNameIp
{
    public static function matches(string $requestIp): bool
    {
        $method = substr_count($requestIp, ':') > 1 ? 'checkIp6' : 'checkIp4';

        return self::$method($requestIp); // @mago-expect analysis:string-member-selector
    }

    public function viaInstance(string $requestIp): bool
    {
        $method = 'checkHost';

        return $this->$method($requestIp) && $this?->$method($requestIp); // @mago-expect analysis:string-member-selector,string-member-selector,redundant-nullsafe-operator
    }

    public function viaCallable(): Closure
    {
        $method = 'checkHost';

        return $this->$method(...); // @mago-expect analysis:string-member-selector
    }

    public static function namedCall(string $requestIp): bool
    {
        return self::checkIp6($requestIp) || self::{'checkIp4'}($requestIp); // @mago-expect analysis:string-member-selector
    }

    private static function checkIp6(string $ip): bool
    {
        return $ip !== '';
    }

    private static function checkIp4(string $ip): bool
    {
        return $ip !== '';
    }

    private function checkHost(string $host): bool
    {
        return $host !== '';
    }
}
