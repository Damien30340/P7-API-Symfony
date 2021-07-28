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

        return $this->json([
            'phoneList' => $phoneList
        ], 200, [], [
            'groups' => ['list']
        ]);
    }

    /**
     * @param $id
     * @param PhoneRepository $phoneRepository
     * @return Response
     */
    public function phoneDetails($id, PhoneRepository $phoneRepository): Response
    {
        $phone = $phoneRepository->findOneBy(['id' => $id]);

        return $this->json([
            'phone' => $phone
        ], 200, [], [
            'groups' => ['details']
        ]);
    }
}
