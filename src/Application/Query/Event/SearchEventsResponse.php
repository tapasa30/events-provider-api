<?php

declare(strict_types=1);

namespace App\Application\Query\Event;

use App\Domain\DTO\Event\EventSummaryDTO;
use App\Domain\Query\QueryResponseInterface;

class SearchEventsResponse implements QueryResponseInterface
{
    /**
     * @param array<EventSummaryDTO> $eventList
     */
    public function __construct(
        private readonly array $eventList
    ) {
    }

    public function toPrimitives(): array
    {
        $eventList = [];

        foreach ($this->eventList as $event) {
            $eventList[] = $event->toPrimitives();
        }

        return $eventList;
    }
}