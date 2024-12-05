<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use Ramsey\Uuid\Uuid;

class Zone
{
    private Event $event;

    public function __construct(
        private readonly string $id,
        private readonly string $externalId,
        private readonly string $name,
        private readonly int $capacity,
        private readonly int $price,
        private readonly bool $isNumbered,
    ) {
    }

    /**
     * @param array<string, string|int|bool> $rawZone
     */
    public static function createFromRaw(array $rawZone, Event $event): self
    {
        $zone = new self(
            Uuid::uuid4()->toString(),
            $rawZone['external_id'],
            $rawZone['name'],
            (int)$rawZone['capacity'],
            (int)$rawZone['price'],
            (bool)$rawZone['is_numbered'],
        );

        $zone->setEvent($event);

        return $zone;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function setEvent(Event $event): void
    {
        $this->event = $event;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCapacity(): int
    {
        return $this->capacity;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function isNumbered(): bool
    {
        return $this->isNumbered;
    }
}
