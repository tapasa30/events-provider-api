<?php

declare(strict_types=1);

namespace App\Domain\Cache;

use App\Application\Query\Event\SearchEventsResponse;

interface EventsListResponseCacheInterface
{
    public function getResponse(string $cacheKey): ?SearchEventsResponse;
    public function storeResponse(string $cacheKey, SearchEventsResponse $eventListResponse): void;
    public function exists(string $cacheKey): bool;
    public function invalidate(string $cacheKey): void;
}