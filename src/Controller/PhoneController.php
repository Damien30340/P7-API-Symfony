<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PhoneController
 * @package App\Controller
 */
#[Route(path: '/api/v1')]
class PhoneController extends AbstractController
{
    /**
     * @param PhoneRepository $phoneRepository
     * @return JsonResponse
     */
    #[Route(path: '/phone', name: 'list_phone', methods: 'GET')]
    // TODO à paginer
    public function list(PhoneRepository $phoneRepository): JsonResponse
    {
        $phoneList = $phoneRepository->findAll();

        return $this->json([
            'PhoneList' => $phoneList,
        ], 200, [], [
            'groups' => ['list']
        ]);
    }


    /**
     * @param Phone $phone
     * @return JsonResponse
     */
    #[Route(path: '/phone/{id}', name: 'details_phone', methods: 'GET')]
    public function details(Phone $phone): JsonResponse
    {
        return $this->json([
            'phone' => $phone,
        ], 200, [], [
            'groups' => ['details']
        ]);
    }
}
