<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for($i = 0; $i < 150; $i++){
            $address = (new Address())
                ->setNumber($faker->buildingNumber())
                ->setStreet($faker->streetName())
                ->setPostCode(intval($faker->postcode()))
                ->setCity($faker->city())
                ->setCountry($faker->country());
            $user = (new User())
                ->setNickName($faker->userName())
                ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setPhoneNumber($faker->phoneNumber())
                ->setMail($faker->email())
                ->setPassword($faker->password(4, 9))
                ->addAddress($address);

            $manager->persist($user);
        }
        $manager->flush();
    }
}
