<?php

declare(strict_types=1);

namespace App\Tests\Application\Command;

use App\Application\Command\Event\CreateEventCommand;
use App\Application\Command\Event\CreateEventCommandHandler;
use App\Domain\Repository\EventRepositoryInterface;
use App\Domain\Repository\ZoneRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CreateEventCommandHandlerTest extends TestCase
{
    private EventRepositoryInterface|MockObject $eventRepositoryMock;
    private ZoneRepositoryInterface|MockObject $zoneRepositoryMock;

    protected function setUp(): void
    {
        $this->eventRepositoryMock = $this->createMock(EventRepositoryInterface::class);
        $this->zoneRepositoryMock = $this->createMock(ZoneRepositoryInterface::class);
    }

    public function testEventSavesCorrectlyWithoutZones(): void
    {
        $createEventCommand = new CreateEventCommand(
            'event_external_id',
            'event_name',
            new \DateTimeImmutable(),
            new \DateTimeImmutable(),
            []
        );

        $this->eventRepositoryMock
            ->expects(self::once())
            ->method('save');

        $this->zoneRepositoryMock
            ->expects(self::never())
            ->method('save');

        $commandHandler = new CreateEventCommandHandler(
            $this->eventRepositoryMock,
            $this->zoneRepositoryMock,
        );

        $commandHandler($createEventCommand);
    }

    public function testEventSavesCorrectlyWithZones(): void
    {
        $createEventCommand = new CreateEventCommand(
            'event_external_id',
            'event_name',
            new \DateTimeImmutable(),
            new \DateTimeImmutable(),
            [
                [
                    'external_id' => Uuid::uuid4()->toString(),
                    'name' => 'event_name_1',
                    'capacity' => 34,
                    'price' => 23.99,
                    'is_numbered' => false,
                ],
                [
                    'external_id' => Uuid::uuid4()->toString(),
                    'name' => 'event_name_2',
                    'capacity' => 54,
                    'price' => 23.99,
                    'is_numbered' => false,
                ],
                [
                    'external_id' => Uuid::uuid4()->toString(),
                    'name' => 'event_name_3',
                    'capacity' => 12,
                    'price' => 23.99,
                    'is_numbered' => false,
                ],
            ]
        );

        $this->eventRepositoryMock
            ->expects(self::once())
            ->method('save');

        $this->zoneRepositoryMock
            ->expects(self::exactly(3))
            ->method('save');

        $commandHandler = new CreateEventCommandHandler(
            $this->eventRepositoryMock,
            $this->zoneRepositoryMock,
        );

        $commandHandler($createEventCommand);
    }
}