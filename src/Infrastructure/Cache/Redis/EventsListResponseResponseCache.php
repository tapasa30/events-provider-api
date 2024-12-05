<?php

declare(strict_types=1);

namespace App\Infrastructure\Cache\Redis;

use App\Application\Query\Event\SearchEventsResponse;
use App\Domain\Cache\EventsListResponseCacheInterface;
use Psr\Cache\CacheItemPoolInterface;

class EventsListResponseResponseCache implements EventsListResponseCacheInterface
{
    public function __construct(private readonly CacheItemPoolInterface $cacheEventsDefault)
    {
    }

    public function getResponse(string $cacheKey): ?SearchEventsResponse
    {
        if (!$this->exists($cacheKey)) {
            return null;
        }

        return $this->cacheEventsDefault->getItem($cacheKey)->get();
    }

    public function storeResponse(string $cacheKey, SearchEventsResponse $eventListResponse): void
    {
        $item = $this->cacheEventsDefault->getItem($cacheKey);

        $item->set($eventListResponse);

        $this->cacheEventsDefault->save($item);
    }

    public function exists(string $cacheKey): bool
    {
        return $this->cacheEventsDefault->getItem($cacheKey)->isHit();
    }

    public function invalidate(string $cacheKey): void
    {
        $this->cacheEventsDefault->deleteItem($cacheKey);
    }
}
