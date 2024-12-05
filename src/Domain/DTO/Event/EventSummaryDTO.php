<?php

declare(strict_types=1);

namespace App\Domain\DTO\Event;

class EventSummaryDTO
{
    public function __construct(
        private readonly string $id,
        private readonly string $title,
        private readonly \DateTimeInterface $startDateTime,
        private readonly \DateTimeInterface $endDateTime,
        private readonly int $minPrice,
        private readonly int $maxPrice,
    ) {
    }

    public function toPrimitives(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'start_date' => $this->startDateTime->format('Y-m-d'),
            'start_time' => $this->startDateTime->format('H:i:s'),
            'end_date' => $this->endDateTime->format('Y-m-d'),
            'end_time' => $this->endDateTime->format('H:i:s'),
            'min_price' => $this->minPrice,
            'max_price' => $this->maxPrice,
        ];
    }
}