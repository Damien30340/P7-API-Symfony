<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BrandFixtures extends Fixture
{
    public const PHONEBRAND = [
        'Samsung',
        'Huawei',
        'Apple'
    ];

    public function load(ObjectManager $manager)
    {
        foreach(self::PHONEBRAND as $index => $data){
            $brand = (new Brand())->setName(self::PHONEBRAND[$index]);
            $manager->persist($brand);

            $this->addReference('brand_' . $index, $brand);
        }
        $manager->flush();
    }
}
