<?php

namespace Infrastructure\Util\Serializations;

use JMS\Serializer\Metadata\PropertyMetadata;
use JMS\Serializer\Naming\PropertyNamingStrategyInterface;

final class IdenticalPropertyNamingStrategy implements PropertyNamingStrategyInterface
{
    public function translateName(PropertyMetadata $property): string
    {

        return $property->name."";
    }
}
