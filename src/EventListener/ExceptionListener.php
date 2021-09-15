<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExceptionListener
{
    public function jsonException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        $statusCode = method_exists($exception, 'getStatusCode')?$exception->getStatusCode():$exception->getCode();
        $message = $event->getThrowable()->getMessage();
        if($statusCode === 0) $statusCode = 500;

        $response = new JsonResponse([
            'message' => $message,
            'code' => $statusCode
        ], $statusCode);

        $event->setResponse($response);
    }
}