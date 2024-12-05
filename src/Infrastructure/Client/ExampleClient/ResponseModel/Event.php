<?php

declare(strict_types=1);

namespace App\Infrastructure\Client\ExampleClient\ResponseModel;

use JMS\Serializer\Annotation as Serializer;

class Event
{
    #[Serializer\SerializedName('event_id')]
    #[Serializer\XmlAttribute]
    private int $eventId;

    #[Serializer\SerializedName('sold_out')]
    #[Serializer\XmlAttribute]
    private bool $soldOut;

    #[Serializer\Type("DateTimeImmutable<'Y-m-d\TH:i:s'>")]
    #[Serializer\SerializedName('sell_from')]
    #[Serializer\XmlAttribute]
    private \DateTimeInterface $sellFrom;

    #[Serializer\Type("DateTimeImmutable<'Y-m-d\TH:i:s'>")]
    #[Serializer\SerializedName('sell_to')]
    #[Serializer\XmlAttribute]
    private \DateTimeInterface $sellTo;

    #[Serializer\Type("DateTimeImmutable<'Y-m-d\TH:i:s'>")]
    #[Serializer\SerializedName('event_start_date')]
    #[Serializer\XmlAttribute]
    private \DateTimeInterface $eventStartDate;

    #[Serializer\Type("DateTimeImmutable<'Y-m-d\TH:i:s'>")]
    #[Serializer\SerializedName('event_end_date')]
    #[Serializer\XmlAttribute]
    private \DateTimeInterface $eventEndDate;

    /** @var array<Zone> $zones */
    #[Serializer\Type("array<App\Infrastructure\Client\ExampleClient\ResponseModel\Zone>")]
    #[Serializer\XmlList(entry: 'zone', inline: true)]
    private array $zones;

    public function getEventId(): int
    {
        return $this->eventId;
    }

    public function isSoldOut(): bool
    {
        return $this->soldOut;
    }

    public function getSellFrom(): \DateTimeInterface
    {
        return $this->sellFrom;
    }

    public function getSellTo(): \DateTimeInterface
    {
        return $this->sellTo;
    }

    public function getEventStartDate(): \DateTimeInterface
    {
        return $this->eventStartDate;
    }

    public function getEventEndDate(): \DateTimeInterface
    {
        return $this->eventEndDate;
    }

    /**
     * @return array<Zone>
     */
    public function getZones(): array
    {
        return $this->zones;
    }
}