<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Application\Command\Event\CreateEventCommand;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;

class Event
{
    /**
     * @var Collection<Zone>
     */
    private Collection $zones;

    public function __construct(
        private readonly string $id,
        private readonly string $externalId,
        private readonly string $title,
        private readonly \DateTimeInterface $startDateTime,
        private readonly \DateTimeInterface $endDateTime,
    ) {
        $this->zones = new ArrayCollection();
    }

    public static function fromCreateCommand(CreateEventCommand $createEventCommand): self
    {
        return new self(
            Uuid::uuid4()->toString(),
            $createEventCommand->getExternalId(),
            $createEventCommand->getTitle(),
            $createEventCommand->getStartDateTime(),
            $createEventCommand->getEndDateTime(),
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getStartDateTime(): \DateTimeInterface
    {
        return $this->startDateTime;
    }

    public function getEndDateTime(): \DateTimeInterface
    {
        return $this->endDateTime;
    }

    public function getZones(): Collection
    {
        return $this->zones;
    }

    public function addZone(Zone $zone): void
    {
        $this->zones->add($zone);
    }
}
