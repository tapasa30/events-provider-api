<?php

declare(strict_types=1);

namespace App\Infrastructure\Client\ExampleClient;

use App\Infrastructure\Client\ExampleClient\ResponseModel\EventList;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;

class ExampleClient
{
    private Serializer $serializer;

    public function __construct(private readonly string $eventsUrl)
    {
        $this->serializer = SerializerBuilder::create()->build();
    }

    public function getEventList(): EventList
    {
        $xmlContents = file_get_contents($this->eventsUrl);

        if ($xmlContents === false) {
            throw new \RuntimeException('Error parsing XML'); // TODO
        }

        return $this->serializer->deserialize($xmlContents, EventList::class, 'xml');
    }
}