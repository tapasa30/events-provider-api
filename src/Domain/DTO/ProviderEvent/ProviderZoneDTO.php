<?php

declare(strict_types=1);

namespace App\Domain\DTO\ProviderEvent;

class ProviderZoneDTO
{
    public function __construct(
        private readonly string $externalId,
        private readonly string $name,
        private readonly int $price,
        private readonly int $capacity,
        private readonly bool $isNumbered,
    ) {
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getCapacity(): int
    {
        return $this->capacity;
    }

    public function isNumbered(): bool
    {
        return $this->isNumbered;
    }
}