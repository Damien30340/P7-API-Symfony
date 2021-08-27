<?php

namespace App\Service;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;

class RequestEncoder
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param string $groups
     * @param Object $obj
     * @return string
     */
    public function encodeEntity(string $groups, Object $obj): string
    {
        $context = SerializationContext::create()->setGroups(array($groups));
        return $this->serializer->serialize($obj, 'json', $context);
    }

    /**
     * @param string $groups
     * @param array $obj
     * @return string
     */
    public function encodeArray(string $groups, array $obj): string
    {
        $context = SerializationContext::create()->setGroups(array($groups));
        return $this->serializer->serialize($obj, 'json', $context);
    }
}