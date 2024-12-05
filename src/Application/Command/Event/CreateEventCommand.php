<?php

declare(strict_types=1);

namespace App\Application\Command\Event;

use App\Domain\Command\CommandInterface;

class CreateEventCommand implements CommandInterface
{
    public function __construct(
        private readonly string $externalId,
        private readonly string $title,
        private readonly \DateTimeInterface $startDateTime,
        private readonly \DateTimeInterface $endDateTime,
        private readonly array $rawZones,
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

    public function getRawZones(): array
    {
        return $this->rawZones;
    }
}