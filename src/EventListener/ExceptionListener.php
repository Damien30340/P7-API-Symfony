<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExceptionListener
{
    public function jsonException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable()->getMessage();
        //TODO Voir avec Seb (doc Symfony pourquoi set après response)
        $response = new JsonResponse([
            'message' => $exception,
            'code' => 400
        ], 400);

        $response->setStatusCode(400);
        $event->setResponse($response);
    }
}