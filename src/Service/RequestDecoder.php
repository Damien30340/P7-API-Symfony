<?php

namespace App\Service;

use App\Entity\Address;
use App\Entity\Client;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class RequestDecoder
{
    private array $encoders;
    /**
     * @var ObjectNormalizer[]
     */
    private array $normalizers;
    private Serializer $serializer;
    private ?Request $request;

    public function __construct(RequestStack $stack)
    {
        $this->request = $stack->getMainRequest();
        $this->encoders = [new XmlEncoder(), new JsonEncoder()];
        $this->normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($this->normalizers, $this->encoders);
    }

    /**
     * @param Client $client
     * @return User
     */
    public function createUser(Client $client): User
    {
        /** @var User $user */
        $user = $this->decodeRequestTo(User::class);
        $user->setClient($client);
        return $user;
    }

    /**
     * @param User $user
     */
    public function updateUser(User $user)
    {
        $decodedUser = $this->decodeRequestTo(User::class);
        $user
            ->setNickName($decodedUser->getNickName())
            ->setFirstName($decodedUser->getFirstName())
            ->setLastName($decodedUser->getLastName())
            ->setMail($decodedUser->getMail())
            ->setPhoneNumber($decodedUser->getPhoneNumber());
    }

    /**
     * @param User $user
     */
    public function createAddress(User $user)
    {
        $address = $this->decodeRequestTo(Address::class);
        $user->addAddress($address);
    }

    /**
     * @param User $user
     */
    public function updateAddress(User $user)
    {
        $decodedAddress = $this->decodeRequestTo(Address::class);
        $user->getAddress()->first()
            ->setNumber($decodedAddress->getNumber())
            ->setStreet($decodedAddress->getStreet())
            ->setPostCode($decodedAddress->getPostCode())
            ->setCity($decodedAddress->getCity())
            ->setCountry($decodedAddress->getCountry());
    }

    public function decodeRequestTo(string $classname){
        return $this->serializer->deserialize($this->request->getContent(), $classname, "json");
    }
}