<?php

declare(strict_types=1);

namespace App\Infrastructure\Client\ExampleClient\ResponseModel;

use JMS\Serializer\Annotation as Serializer;

class EventList
{
    #[Serializer\Type(Output::class)]
    #[Serializer\SerializedName("output")]
    #[Serializer\XmlElement]
    private Output $output;

    public function getOutput(): Output
    {
        return $this->output;
    }
}