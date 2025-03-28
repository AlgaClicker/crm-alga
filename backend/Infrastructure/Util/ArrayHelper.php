<?php
namespace Infrastructure\Util;

use LaravelDoctrine\ORM\Serializers\ArraySerializer;

class ArrayHelper
{
    private $arraySerializer;

    public static function getSubscribingMethods()
    {

        return [
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => 'uuid',
                'method' => 'serializeUuidToJson',
            ],
        ];
    }
    public function __construct()
    {
        $this->arraySerializer = new ArraySerializer();
    }

    private function jsonTransform($entity, $serializer)
    {
        return $serializer->serialize($entity);
    }


    public function entityToArray($entity)
    {
        return $this->jsonTransform($entity, $this->arraySerializer);
    }

    public function collectionToArray($collection)
    {
        $collectionToJson = [];

        foreach ($collection as $entity) {
            $collectionToJson[] = $this->jsonTransform($entity, $this->arraySerializer);
        }

        return $collectionToJson;
    }
}
