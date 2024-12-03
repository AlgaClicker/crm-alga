<?php

namespace Infrastructure\Util;

use JMS\Serializer\Exception\Exception;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\Handler\HandlerRegistryInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\Context;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use JMS\Serializer\Expression\ExpressionEvaluator;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Lazy\LazyUuidFromString as Uuid;


/**
 * Class JsonHelper
 * @package RZ2\Commons\Infrastructure\Util
 */

class ApiHandler implements SubscribingHandlerInterface
{
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

    public function serializeUuidToJson(JsonSerializationVisitor $visitor,  $date, array $type, Context $context) {

        return $date;
    }

    public function serializeDateTimeToJson(JsonSerializationVisitor $visitor, \DateTime $date, array $type, Context $context)
    {

        return $date->format($type['params'][0]);
    }

    public function deserializeDateTimeToJson(JsonDeserializationVisitor $visitor, $dateAsString, array $type, Context $context)
    {
        return new \DateTime($dateAsString);
    }
}


class JMSHelper
{
    private $serializer;

    public function __construct()
    {
        $this->serializer = SerializerBuilder::create()
                            ->addDefaultHandlers()
                            ->addMetadataDir(__DIR__ . "/../../Infrastructure/Doctrine/Serializer")
                            ->configureHandlers(function(HandlerRegistryInterface $registry) {
                                return $registry->registerSubscribingHandler(new ApiHandler());
                            })
                            ->setDebug(env('APP_DEBUG',false))
                            ->setExpressionEvaluator(new ExpressionEvaluator(new ExpressionLanguage()))
                            ->setSerializationContextFactory(function () {
                                return SerializationContext::create()->setSerializeNull(false);
                            })
                            ->build();
    }

    public function toJson($entity)
    {




        if (is_object($entity) || is_array($entity)) {
            try {
                return $this->serializer->serialize($entity, 'json', SerializationContext::create());
            } catch (\Exception $e) {
                abort(500, $e->getMessage());
            }
        }

        return $this->serializer->serialize($entity, 'json', SerializationContext::create());
    }

    public function toArray($entity)
    {
        return $this->serializer->serialize($entity, 'array', SerializationContext::create()->setVersion(1));
    }

    public function toXml($entity)
    {
        return $this->serializer->serialize($entity, 'xml');
    }


}
