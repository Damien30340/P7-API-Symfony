<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ClientFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordEncoder;

    public function __construct(UserPasswordHasherInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $client = new Client();
        $client
            ->setUsername('testApi')
            ->setMail($faker->email())
            ->setSiren($faker->numberBetween(111111111, 999999999))
            ->setPhoneNumber($faker->phoneNumber())
            ->setPassword($this->passwordEncoder->hashPassword($client, "testApi"));

        $manager->persist($client);

        $this->addReference('client_' . 0, $client);

        for($i = 1; $i < 6; $i++){
            $client = new Client();
            $client
                ->setUsername($faker->lastName())
                ->setMail($faker->email())
                ->setSiren($faker->numberBetween(111111111, 999999999))
                ->setPhoneNumber($faker->phoneNumber())
                ->setPassword($this->passwordEncoder->hashPassword($client, "123456789aA@"));

            $manager->persist($client);

            $this->addReference('client_' . $i, $client);
        }
        $manager->flush();
    }

}
