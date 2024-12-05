<?php

declare(strict_types=1);

namespace App\Infrastructure\EventProvider\ExampleEventProvider;

use App\Domain\DTO\ProviderEvent\ProviderEventDTO;
use App\Domain\DTO\ProviderEvent\ProviderZoneDTO;
use App\Domain\Service\EventProviderInterface;
use App\Infrastructure\Client\ExampleClient\ExampleClient;
use App\Infrastructure\Client\ExampleClient\ResponseModel\Zone;

class ExampleEventProvider implements EventProviderInterface
{
    public function __construct(private readonly ExampleClient $exampleEventProviderClient)
    {
    }

    /**
     * @return array<ProviderEventDTO>
     */
    public function fetchEvents(): array
    {
        $eventList = $this->exampleEventProviderClient->getEventList();
        $providerEvents = [];

        foreach ($eventList->getOutput()->getBaseEvents() as $event) {
            if ($event->getSellMode() !== 'online') {
                continue;
            }

            $zonesDtoList = $this->generateZonesDto($event->getEvent()->getZones());

            $providerEventDto = new ProviderEventDTO(
                (string)$event->getEvent()->getEventId(),
                $event->getTitle(),
                $event->getEvent()->getEventStartDate(),
                $event->getEvent()->getEventEndDate(),
                $zonesDtoList
            );

            $providerEvents[] = $providerEventDto;
        }

        return $providerEvents;
    }

    /**
     * @param array<Zone> $zoneModels
     * @return array<ProviderZoneDTO>
     */
    private function generateZonesDto(array $zoneModels): array
    {
        $zones = [];

        foreach ($zoneModels as $zoneModel) {
            $zones[] = new ProviderZoneDTO(
                (string)$zoneModel->getZoneId(),
                $zoneModel->getName(),
                $zoneModel->getPrice(),
                $zoneModel->getCapacity(),
                $zoneModel->isNumbered(),
            );
        }

        return $zones;
    }
}