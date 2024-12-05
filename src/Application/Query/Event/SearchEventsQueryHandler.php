<?php

declare(strict_types=1);

namespace App\Application\Query\Event;

use App\Application\Service\CacheKeyGeneratorService;
use App\Domain\Cache\EventsListResponseCacheInterface;
use App\Domain\Query\QueryHandlerInterface;
use App\Domain\Repository\EventRepositoryInterface;

class SearchEventsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly EventsListResponseCacheInterface $eventsListResponseCache,
        private readonly EventRepositoryInterface $eventRepository
    ) {
    }

    public function __invoke(SearchEventsQuery $searchEventsQuery): SearchEventsResponse
    {
        $startsAtDate = $searchEventsQuery->getStartsAtDate();
        $endsAtDate = $searchEventsQuery->getEndsAtDate();

        $queryResponseCacheKey = CacheKeyGeneratorService::generateCacheKey([
            'starts_at_date' => $startsAtDate?->format('Y-m-d'),
            'ends_at_date' => $endsAtDate?->format('Y-m-d')
        ]);

        if ($this->eventsListResponseCache->exists($queryResponseCacheKey)) {
            return $this->eventsListResponseCache->getResponse($queryResponseCacheKey);
        }

        $eventList = $this->eventRepository->findSummaryByDateRange($startsAtDate, $endsAtDate);
        $searchEventsResponse = new SearchEventsResponse($eventList);

        $this->eventsListResponseCache->storeResponse($queryResponseCacheKey, $searchEventsResponse);

        return $searchEventsResponse;
    }
}