<?php


namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;

class Manager
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->em = $manager;
    }

    public function update($obj){
        $this->em->persist($obj);
        $this->em->flush();
    }

    public function delete($obj){
        $this->em->remove($obj);
        $this->em->flush();
    }
}