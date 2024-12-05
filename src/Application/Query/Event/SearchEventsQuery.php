<?php

declare(strict_types=1);

namespace App\Application\Query\Event;

use App\Domain\Query\QueryInterface;

class SearchEventsQuery implements QueryInterface
{
    public function __construct(
        private readonly ?\DateTimeInterface $startsAtDate,
        private readonly ?\DateTimeInterface $endsAtDate
    ) {
    }

    public function getStartsAtDate(): ?\DateTimeInterface
    {
        return $this->startsAtDate;
    }

    public function getEndsAtDate(): ?\DateTimeInterface
    {
        return $this->endsAtDate;
    }
}