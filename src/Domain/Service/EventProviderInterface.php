<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\DTO\ProviderEvent\ProviderEventDTO;

interface EventProviderInterface
{
    /** @return array<ProviderEventDTO> */
    public function fetchEvents(): array;
}