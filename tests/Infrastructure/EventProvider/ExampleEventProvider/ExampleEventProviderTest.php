<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\EventProvider\ExampleEventProvider;

use App\Domain\DTO\ProviderEvent\ProviderEventDTO;
use App\Infrastructure\Client\ExampleClient\ExampleClient;
use App\Infrastructure\Client\ExampleClient\ResponseModel\EventList;

use App\Infrastructure\EventProvider\ExampleEventProvider\ExampleEventProvider;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ExampleEventProviderTest extends TestCase
{
    private ExampleClient|MockObject $exampleClient;
    private Serializer $serializer;

    protected function setUp(): void
    {
        $this->exampleClient = $this->createMock(ExampleClient::class);
        $this->serializer = SerializerBuilder::create()->build();
    }

    public function testReturnsOnlineEventsTest(): void
    {
        $onlineEventList = $this->serializer->deserialize(file_get_contents(__DIR__.'/testOnlineEvents.xml'), EventList::class, 'xml');

        $expectedProviderEvents = [
            new ProviderEventDTO(
                '501',
                'Rock Festival 2024',
                new \DateTime('2024-08-15T19:00:00'),
                new \DateTime('2024-08-15T22:00:00'),
                []
            )
        ];

        $this->exampleClient
            ->expects(self::once())
            ->method('getEventList')
            ->willReturn($onlineEventList);

        $exampleEventProvider = new ExampleEventProvider($this->exampleClient);

        $providerEvents = $exampleEventProvider->fetchEvents();

        $this->assertEquals($expectedProviderEvents, $providerEvents);
    }
}