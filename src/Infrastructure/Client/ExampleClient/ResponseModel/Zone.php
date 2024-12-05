<?php

declare(strict_types=1);

namespace App\Infrastructure\Client\ExampleClient\ResponseModel;

use JMS\Serializer\Annotation as Serializer;

class Zone
{
    #[Serializer\SerializedName('zone_id')]
    #[Serializer\XmlAttribute]
    private int $zoneId;

    #[Serializer\SerializedName('capacity')]
    #[Serializer\XmlAttribute]
    private int $capacity;

    #[Serializer\SerializedName('price')]
    #[Serializer\XmlAttribute]
    private int $price;

    #[Serializer\SerializedName('name')]
    #[Serializer\XmlAttribute]
    private string $name;

    #[Serializer\SerializedName('numbered')]
    #[Serializer\XmlAttribute]
    private bool $numbered;

    public function getZoneId(): int
    {
        return $this->zoneId;
    }

    public function getCapacity(): int
    {
        return $this->capacity;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isNumbered(): bool
    {
        return $this->numbered;
    }
}