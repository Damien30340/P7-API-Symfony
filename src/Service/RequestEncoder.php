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
     * @param array $groups
     * @param Object $obj
     * @return string
     */
    public function encodeEntity(array $groups, Object $obj): string
    {
        array_push($groups, 'Default');
        $context = SerializationContext::create()->setGroups($groups);
        return $this->serializer->serialize($obj, 'json', $context);
    }

}