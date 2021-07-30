<?php

namespace App\Service;

use App\Entity\Address;
use App\Entity\User;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class EncoderJson
{
    private array $encoders;
    /**
     * @var ObjectNormalizer[]
     */
    private array $normalizers;
    private Serializer $serializer;

    public function __construct()
    {
        $this->encoders = [new XmlEncoder(), new JsonEncoder()];
        $this->normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($this->normalizers, $this->encoders);
    }

    /**
     * @param $getContent
     * @return mixed|void
     */
    public function decodeUserAdd($getContent){
        $getContent !== null ? $jsonContent = json_decode($getContent): $jsonContent = null;
        if($jsonContent !== null){
            if(!empty($jsonContent->{"address"})){
                try {
                    $address = $this->serializer->denormalize(
                        [
                            "number" => $jsonContent->{"address"}->{"number"},
                            "street" => $jsonContent->{"address"}->{"street"},
                            "postCode" => $jsonContent->{"address"}->{"postCode"},
                            "city" => $jsonContent->{"address"}->{"city"},
                            "country" => $jsonContent->{"address"}->{"country"}
                        ], Address::class
                    );
                } catch (ExceptionInterface $e) {
                }
                try {
                    $user = $this->serializer->denormalize(
                        [
                            "nickName" => $jsonContent->{"user"}->{"nickName"},
                            "firstName" => $jsonContent->{"user"}->{"firstName"},
                            "lastName" => $jsonContent->{"user"}->{"lastName"},
                            "mail" => $jsonContent->{"user"}->{"mail"},
                            "password" => $jsonContent->{"user"}->{"password"},
                            "phoneNumber" => $jsonContent->{"user"}->{"phoneNumber"},
                            "addAddress" => $address
                        ], User::class);
                } catch (ExceptionInterface $e) {
                }
            } else {
                try {
                    $user = $this->serializer->denormalize(
                        [
                            "nickName" => $jsonContent->{"user"}->{"nickName"},
                            "firstName" => $jsonContent->{"user"}->{"firstName"},
                            "lastName" => $jsonContent->{"user"}->{"lastName"},
                            "mail" => $jsonContent->{"user"}->{"mail"},
                            "password" => $jsonContent->{"user"}->{"password"},
                            "phoneNumber" => $jsonContent->{"user"}->{"phoneNumber"}
                        ], User::class);
                } catch (ExceptionInterface $e) {
                }
            }
            return $user;
        }
    }

    public function decodeUserUpdate($getContent, ?User $user): ?User
    {
        /** @var User $update */
        $update = $this->decodeUserAdd($getContent);

        $user
            ->setNickName($update->getNickName()!=null?$update->getNickName():$user->getNickName())
            ->setFirstName($update->getFirstName()!=null?$update->getFirstName():$user->getFirstName())
            ->setLastName($update->getLastName()!=null?$update->getLastName():$user->getLastName())
            ->setPhoneNumber($update->getPhoneNumber()!=null?$update->getPhoneNumber():$user->getPhoneNumber());
        if(count($update->getAddress()) > 0){
            foreach ($update->getAddress() as $item) {
                $address = (new Address())
                    ->setNumber($item->getNumber())
                    ->setStreet($item->getStreet())
                    ->setPostCode($item->getPostCode())
                    ->setCity($item->getCity())
                    ->setCountry($item->getCountry());
            }
            $user->addAddress($address);
        }
        return $user;
    }
}