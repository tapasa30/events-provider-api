<?php

declare(strict_types=1);

namespace App\Application\Service;

class CacheKeyGeneratorService
{
    public static function generateCacheKey(array $array): string
    {
        sort($array);

        $filteredArrayData = array_filter($array);

        return sha1(serialize($filteredArrayData));
    }
}