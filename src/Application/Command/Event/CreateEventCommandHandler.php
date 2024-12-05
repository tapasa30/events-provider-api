<?php

declare(strict_types=1);

namespace App\Application\Command\Event;

use App\Domain\Command\CommandHandlerInterface;
use App\Domain\Entity\Event;
use App\Domain\Entity\Zone;
use App\Domain\Exception\EventAlreadyExistsException;
use App\Domain\Repository\EventRepositoryInterface;
use App\Domain\Repository\ZoneRepositoryInterface;

class CreateEventCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly EventRepositoryInterface $eventRepository,
        private readonly ZoneRepositoryInterface $zoneRepository,
    ) {
    }

    public function __invoke(CreateEventCommand $createEventCommand): void
    {
        $existingEvent = $this->eventRepository->findByExternalId($createEventCommand->getExternalId());

        if ($existingEvent !== null) {
            throw new EventAlreadyExistsException();
        }

        $event = Event::fromCreateCommand($createEventCommand);

        $this->eventRepository->save($event);

        foreach ($createEventCommand->getRawZones() as $rawZone) {
            $zone = Zone::createFromRaw($rawZone, $event);

            $this->zoneRepository->save($zone);
            $event->addZone($zone);
        }
    }
}