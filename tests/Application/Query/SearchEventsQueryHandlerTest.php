<?php

declare(strict_types=1);

namespace App\Tests\Application\Query;

use App\Application\Query\Event\SearchEventsQuery;
use App\Application\Query\Event\SearchEventsQueryHandler;
use App\Application\Query\Event\SearchEventsResponse;
use App\Domain\Cache\EventsListResponseCacheInterface;
use App\Domain\DTO\Event\EventSummaryDTO;
use App\Domain\Repository\EventRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class SearchEventsQueryHandlerTest extends TestCase
{
    private EventsListResponseCacheInterface|MockObject $eventsListResponseCacheMock;
    private EventRepositoryInterface|MockObject $eventRepositoryMock;

    protected function setUp(): void
    {
        $this->eventsListResponseCacheMock = $this->createMock(EventsListResponseCacheInterface::class);
        $this->eventRepositoryMock = $this->createMock(EventRepositoryInterface::class);
    }

    public function testReturnsCachedData(): void
    {
        $searchEventsQuery = new SearchEventsQuery(
            new \DateTimeImmutable(),
            new \DateTimeImmutable(),
        );

        $expectedEventSummaryDto = new EventSummaryDTO(
            Uuid::uuid4()->toString(),
            'event_title',
            new \DateTimeImmutable(),
            new \DateTimeImmutable(),
            12,
            45
        );

        $expectedSearchEventsResponse = new SearchEventsResponse([$expectedEventSummaryDto]);

        $this->eventsListResponseCacheMock
            ->expects(self::once())
            ->method('exists')
            ->willReturn(true);

        $this->eventsListResponseCacheMock
            ->expects(self::once())
            ->method('getResponse')
            ->willReturn($expectedSearchEventsResponse);

        $this->eventRepositoryMock
            ->expects(self::never())
            ->method('findSummaryByDateRange');

        $this->eventsListResponseCacheMock
            ->expects(self::never())
            ->method('storeResponse');

        $searchEventsQueryHandler = new SearchEventsQueryHandler(
            $this->eventsListResponseCacheMock,
            $this->eventRepositoryMock,
        );

        $searchEventsResponse = $searchEventsQueryHandler($searchEventsQuery);

        $this->assertEquals($expectedSearchEventsResponse, $searchEventsResponse);
    }

    public function testReturnsDatabaseQueryData(): void
    {
        $searchEventsQuery = new SearchEventsQuery(
            new \DateTimeImmutable(),
            new \DateTimeImmutable(),
        );

        $expectedEventSummaryDto = new EventSummaryDTO(
            Uuid::uuid4()->toString(),
            'event_title',
            new \DateTimeImmutable(),
            new \DateTimeImmutable(),
            12,
            45
        );

        $this->eventsListResponseCacheMock
            ->expects(self::once())
            ->method('exists')
            ->willReturn(false);

        $this->eventsListResponseCacheMock
            ->expects(self::never())
            ->method('getResponse');

        $this->eventRepositoryMock
            ->expects(self::once())
            ->method('findSummaryByDateRange')
            ->willReturn([$expectedEventSummaryDto]);

        $this->eventsListResponseCacheMock
            ->expects(self::once())
            ->method('storeResponse');

        $searchEventsQueryHandler = new SearchEventsQueryHandler(
            $this->eventsListResponseCacheMock,
            $this->eventRepositoryMock,
        );

        $searchEventsResponse = $searchEventsQueryHandler($searchEventsQuery);

        $this->assertEquals(new SearchEventsResponse([$expectedEventSummaryDto]), $searchEventsResponse);
    }
}