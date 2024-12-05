<?php

declare(strict_types=1);

namespace App\Domain\DTO\ProviderEvent;

class ProviderEventDTO
{
    public function __construct(
        private readonly string $externalId,
        private readonly string $title,
        private readonly \DateTimeInterface $startDateTime,
        private readonly \DateTimeInterface $endDateTime,
        private readonly array $zones,
    ) {
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

    /**
     * @return array<ProviderZoneDTO>
     */
    public function getZones(): array
    {
        return $this->zones;
    }
}