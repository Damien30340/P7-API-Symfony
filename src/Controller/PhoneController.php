<?php

namespace App\Controller;

use App\Repository\PhoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PhoneController
 * @package App\Controller
 */
class PhoneController extends AbstractController
{
    /**
     * @param PhoneRepository $phoneRepository
     * @return Response
     */
    public function list(PhoneRepository $phoneRepository): Response
    {
        $phoneList = $phoneRepository->findAll();

        $list = [];


        foreach ($phoneList as $index => $phone){
            array_push($list, ['brand' => $phone->getBrand(), $phone]);
        }

        return $this->json([
            'PhoneList' => $list
        ], 200, [], [
            'groups' => ['list']
        ]);
    }

    /**
     * @param $id
     * @param PhoneRepository $phoneRepository
     * @return Response
     */
    public function details($id, PhoneRepository $phoneRepository): Response
    {
        $phone = $phoneRepository->findOneBy(['id' => $id]);
        $brand = $phone->getBrand();

        return $this->json([
            'brand' => $brand,
            'phone' => $phone
        ], 200, [], [
            'groups' => ['details']
        ]);
    }
}
