<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Entity\Phone;
use App\Entity\Picture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public const PHONELIBEL = [
        'Galaxy S6',
        'Galaxy S7',
        'Galaxy S8',
        'Galaxy Note 21',
        'Xiaomi Redmi Note 10 Pro',
        'Xiaomi Redmi Note 10 5G',
        'Xiaomi Redmi Note 10 4G',
        'iPhone 12 Pro',
        'iPhone 10'
    ];

    public const PHONEDESCRIPTION = [
        "Le Samsung Galaxy S6 est le haut de gamme de la marque coréenne pour l'année 2015. Il vient admirablement faire oublier le Galaxy S5 et son manque d'innovation grâce à de nombreuses technologies qui très intéressantes. Un écran 5,1 pouces avec résolution QHD, la 4G catégorie 6, un super appareil photo arrière de 16 mégapixels, le tout dans un design de verre et de métal. Ce Galaxy S6 est tout simplement le haut de gamme que mérite Android.",
        "Le Samsung Galaxy S7 est un smartphone haut de gamme au format 5.1 pouces officialisé au MWC 2016. Il tourne sous Android 6.0 Marshmallow à sa sortie et s'appuie sur un processeur Exynos 8890 (ou Qualcomm Snapdragon 820 dans une variante asiatique) assisté par 4 Go de RAM. Le smartphone dispose d'un capteur photo dorsal de 12 mégapixels et d'un capteur frontal de 5 mégapixels. Avec 32 Go de stockage interne, il offre la possibilité d'ajouter une carte microSD jusqu'à 200 Go.",
        "Le Samsung Galaxy S8 est un smartphone de la firme coréenne Samsung, il embarque un processeur Exynos 8895 en Europe ou le Snapdragon 835 dans le reste du monde et un écran sans bordures de 5,8 pouces.",
        "Samsung a en quelque sorte inventé le concept des phablettes — contraction de smartphone et tablette — avec ses premiers Galaxy Note. Or, avec le Galaxy Z Fold 3, le géant sud-coréen proposera un smartphone capable de devenir une tablette à part entière. Cette polyvalence et la présence d’un stylet S Pen lui permettront de prendre la place des Galaxy Note pour séduire les férus de productivité.",
        "Le Xiaomi Redmi Note 10 Pro est un smartphone 4G de la famille « Redmi Note 10 » annoncé en mars 2021. Tourné autour de la photographie, il est équipé d'un capteur principal de 108 mégapixels épaulé par 3 capteurs secondaires de 8+5+2 mégapixels. Il est équipé d'un SoC Qualcomm Snapdragon 732G, d'une batterie de 5020 mAh et d'un écran Super AMOLED Full HD+ 120 Hz.",
        "Le Xiaomi Redmi Note 10 5G est un smartphone 4G et 5G de la famille « Redmi Note 10 » annoncé en mars 2021. Seul smartphone de la gamme compatible 5G grâce à son SoC MediaTek Dimensity 700, il dispose également d'un écran compatible 90 Hz et d'un capteur photo de 48 mégapixels.",
        "Le Redmi Note 10 est un smartphone polyvalent milieu-de-gamme, qui possède non seulement un écran AMOLED DotDisplay de 6,43 pouces, mais également une charge rapide de 33W ainsi qu'une batterie de 5000 mAh (type), une caméra quadruple 48MP et un Qualcomm Snapdragon 678.",
        "L'iPhone 12 Pro est le modèle haut de gamme de la 14e génération de smartphone d'Apple annoncé le 13 octobre 2020. Il est équipé d'un écran de 6,1 pouces OLED HDR 60 Hz, d'un triple capteur photo avec ultra grand-angle et téléobjectif (x4 optique) et d'un SoC Apple A14 Bionic compatible 5G (sub-6 GHz).",
        "L'iPhone X fait parti de la onzième itération du célèbre smartphone d'Apple. Il intègre un SoC Apple A11 Bionic gravé en 10 nm. Il dispose d'un écran borderless de 5,8 pouces et d'un capteur facial pour déverrouiller le smartphone."
    ];

    public const PHONEPRICE = [
        '65.5',
        '99.8',
        '150',
        '250',
        '245',
        '550',
        '398.95',
        '1259.50',
        '857.89'
    ];

    public function load(ObjectManager $manager)
    {
        foreach(self::PHONELIBEL as $index => $data){
            $picture = (new Picture())
            ->setFilename('https://fakeimg.pl/300/');

            $phone = (new Phone())
                ->setName(self::PHONELIBEL[$index])
                ->setDescription(self::PHONEDESCRIPTION[$index])
                ->setPrice(self::PHONEPRICE[$index])
                ->addPicture($picture);

            if($index < 4 ){
                $phone->setBrand($this->getReference('brand_0'));
            } elseif($index > 3 && $index < 7){
                $phone->setBrand($this->getReference('brand_1'));
            } else {
                $phone->setBrand($this->getReference('brand_2'));
            }

            $manager->persist($phone);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            BrandFixtures::class,
        ];
    }
}
