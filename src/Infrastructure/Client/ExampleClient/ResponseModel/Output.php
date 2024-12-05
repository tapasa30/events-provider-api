<?php

declare(strict_types=1);

namespace App\Infrastructure\Client\ExampleClient\ResponseModel;

use JMS\Serializer\Annotation as Serializer;

class Output
{
    /** @var array<BaseEvent> */
    #[Serializer\Type("array<App\Infrastructure\Client\ExampleClient\ResponseModel\BaseEvent>")]
    #[Serializer\SerializedName("base_event")]
    #[Serializer\XmlList(entry: 'base_event', inline: true)]
    private array $baseEvents;

    /**
     * @return array<BaseEvent>
     */
    public function getBaseEvents(): array
    {
        return $this->baseEvents;
    }
}