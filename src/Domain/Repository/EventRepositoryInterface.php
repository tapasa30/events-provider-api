<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\DTO\Event\EventSummaryDTO;
use App\Domain\Entity\Event;

interface EventRepositoryInterface
{
    public function save(Event $workEntry): void;

    /**
     * @return array<EventSummaryDTO>
     */
    public function findSummaryByDateRange(?\DateTimeInterface $startsAtDate, ?\DateTimeInterface $endsAtDate): array;

    public function findByExternalId(string $externalId): ?Event;
}