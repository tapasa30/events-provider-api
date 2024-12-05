<?php

declare(strict_types=1);

namespace App\Infrastructure\Client\ExampleClient\ResponseModel;

use JMS\Serializer\Annotation as Serializer;

class BaseEvent
{
    #[Serializer\SerializedName('base_event_id')]
    #[Serializer\XmlAttribute]
    private int $baseEventId;

    #[Serializer\SerializedName('title')]
    #[Serializer\XmlAttribute]
    private string $title;

    #[Serializer\SerializedName('sell_mode')]
    #[Serializer\XmlAttribute]
    private string $sellMode;

    #[Serializer\Type(Event::class)]
    #[Serializer\SerializedName('event')]
    #[Serializer\XmlElement]
    private Event $event;

    public function getBaseEventId(): int
    {
        return $this->baseEventId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getSellMode(): string
    {
        return $this->sellMode;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }
}