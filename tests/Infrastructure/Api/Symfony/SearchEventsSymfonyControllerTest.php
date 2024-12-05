<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Api\Symfony;

use App\Application\Query\Event\SearchEventsQueryHandler;
use App\Application\Query\Event\SearchEventsResponse;
use App\Domain\DTO\Event\EventSummaryDTO;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\DataProvider;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SearchEventsSymfonyControllerTest extends WebTestCase
{
    public function testReturnsResponseWhenFilterByStartsAt(): void
    {
        $client = static::createClient();

        $searchEventsQueryHandlerMock = $this->createMock(SearchEventsQueryHandler::class);

        $startDateTime = new DateTimeImmutable();
        $endDateTime = new DateTimeImmutable();

        $expectedEventSummary = [
            'id' => Uuid::uuid4()->toString(),
            'title' => 'event_title',
            'start_date' => $startDateTime->format('Y-m-d'),
            'start_time' => $startDateTime->format('H:i:s'),
            'end_date' => $endDateTime->format('Y-m-d'),
            'end_time' => $endDateTime->format('H:i:s'),
            'min_price' => 12,
            'max_price' => 45,
        ];

        $eventSummaryDto = new EventSummaryDTO(
            $expectedEventSummary['id'],
            $expectedEventSummary['title'],
            $startDateTime,
            $endDateTime,
            $expectedEventSummary['min_price'],
            $expectedEventSummary['max_price'],
        );

        $searchEventsResponse = new SearchEventsResponse([$eventSummaryDto]);

        $searchEventsQueryHandlerMock->expects(self::once())
            ->method('__invoke')
            ->willReturn($searchEventsResponse);

        $client->getContainer()->set(SearchEventsQueryHandler::class, $searchEventsQueryHandlerMock);

        $client->request('GET', '/search', [
            'starts_at' => $startDateTime->format('Y-m-d'),
        ]);

        $expectedResponse = [
            'error' => null,
            'data' => [
                'events' => [$expectedEventSummary]
            ]
        ];

        $this->assertEquals(json_encode($expectedResponse), $client->getResponse()->getContent());
    }

    public function testReturnsResponseWhenFilterByEndsAt(): void
    {
        $client = static::createClient();

        $searchEventsQueryHandlerMock = $this->createMock(SearchEventsQueryHandler::class);

        $startDateTime = new DateTimeImmutable();
        $endDateTime = new DateTimeImmutable();

        $expectedEventSummary = [
            'id' => Uuid::uuid4()->toString(),
            'title' => 'event_title',
            'start_date' => $startDateTime->format('Y-m-d'),
            'start_time' => $startDateTime->format('H:i:s'),
            'end_date' => $endDateTime->format('Y-m-d'),
            'end_time' => $endDateTime->format('H:i:s'),
            'min_price' => 12,
            'max_price' => 45,
        ];

        $eventSummaryDto = new EventSummaryDTO(
            $expectedEventSummary['id'],
            $expectedEventSummary['title'],
            $startDateTime,
            $endDateTime,
            $expectedEventSummary['min_price'],
            $expectedEventSummary['max_price'],
        );

        $searchEventsResponse = new SearchEventsResponse([$eventSummaryDto]);

        $searchEventsQueryHandlerMock->expects(self::once())
            ->method('__invoke')
            ->willReturn($searchEventsResponse);

        $client->getContainer()->set(SearchEventsQueryHandler::class, $searchEventsQueryHandlerMock);

        $client->request('GET', '/search', [
            'ends_at' => $endDateTime->format('Y-m-d'),
        ]);

        $expectedResponse = [
            'error' => null,
            'data' => [
                'events' => [$expectedEventSummary]
            ]
        ];

        $this->assertEquals(json_encode($expectedResponse), $client->getResponse()->getContent());
    }

    public function testReturnsResponseWhenFilterByBothDates(): void
    {
        $client = static::createClient();

        $searchEventsQueryHandlerMock = $this->createMock(SearchEventsQueryHandler::class);

        $startDateTime = new DateTimeImmutable();
        $endDateTime = new DateTimeImmutable();

        $expectedEventSummary = [
            'id' => Uuid::uuid4()->toString(),
            'title' => 'event_title',
            'start_date' => $startDateTime->format('Y-m-d'),
            'start_time' => $startDateTime->format('H:i:s'),
            'end_date' => $endDateTime->format('Y-m-d'),
            'end_time' => $endDateTime->format('H:i:s'),
            'min_price' => 12,
            'max_price' => 45,
        ];

        $eventSummaryDto = new EventSummaryDTO(
            $expectedEventSummary['id'],
            $expectedEventSummary['title'],
            $startDateTime,
            $endDateTime,
            $expectedEventSummary['min_price'],
            $expectedEventSummary['max_price'],
        );

        $searchEventsResponse = new SearchEventsResponse([$eventSummaryDto]);

        $searchEventsQueryHandlerMock->expects(self::once())
            ->method('__invoke')
            ->willReturn($searchEventsResponse);

        $client->getContainer()->set(SearchEventsQueryHandler::class, $searchEventsQueryHandlerMock);

        $client->request('GET', '/search', [
            'starts_at' => $startDateTime->format('Y-m-d'),
            'ends_at' => $endDateTime->format('Y-m-d'),
        ]);

        $expectedResponse = [
            'error' => null,
            'data' => [
                'events' => [$expectedEventSummary]
            ]
        ];

        $this->assertEquals(json_encode($expectedResponse), $client->getResponse()->getContent());
    }

    #[DataProvider('badDateFormats')]
    public function testReturnsBadRequestWhenInvalidStartsAtParameterFormat(string $badFormattedStartAtValue): void
    {
        $client = static::createClient();

        $client->request('GET', '/search', [
            'starts_at' => $badFormattedStartAtValue
        ]);

        $this->assertEquals($client->getResponse()->getStatusCode(), Response::HTTP_BAD_REQUEST);
    }

    #[DataProvider('badDateFormats')]
    public function testReturnsBadRequestWhenInvalidEndsAtParameterFormat(string $badFormattedStartAtValue): void
    {
        $client = static::createClient();

        $client->request('GET', '/search', [
            'ends_at' => $badFormattedStartAtValue
        ]);

        $this->assertEquals($client->getResponse()->getStatusCode(), Response::HTTP_BAD_REQUEST);
    }

    public static function badDateFormats(): \iterator
    {
        yield 'slash' => ['2020/11/12'];
        yield 'wrong_year' => ['20201-11-12'];
        yield 'wrong_month' => ['2020-111-12'];
        yield 'wrong_days' => ['2020-11-122'];
        yield 'mixed' => ['2020-11/122'];
    }
}