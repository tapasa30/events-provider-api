<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Zone;

interface ZoneRepositoryInterface
{
    public function save(Zone $zone): void;
}