<?php


namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class EntityPersister
{
    private ?EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $manager)
    {
        $this->em = $manager;
    }

    public function update($obj){
        if($obj != null){
            $this->em->persist($obj);
            $this->em->flush();

            return $obj;
        } else {
            return "Erreur : type object = null";
        }
    }

    public function delete($obj){
        if($obj != null){
            $this->em->remove($obj);
            $this->em->flush();

            return "Object delete";
        } else
        {
            return "Erreur : type object = null";
        }


    }
}