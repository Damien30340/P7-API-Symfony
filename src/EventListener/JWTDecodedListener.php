<?php

namespace App\EventListener;

use App\Repository\ClientRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;

class JWTDecodedListener
{
    /**
     * @param JWTDecodedEvent $event
     *
     * @return void
     */
    public function onJWTDecoded(JWTDecodedEvent $event, ClientRepository $clientRepository)
    {
        $payload = $event->getPayload();
        $client = $clientRepository->findOneBy(['username' => $payload['username']]);
        dd($client);
        $payload['custom_user_data'] = $user->getCustomUserInformations();

        $event->setPayload($payload); // Don't forget to regive the payload for next event / step
    }
}