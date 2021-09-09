<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use App\Service\RequestEncoder;
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
    private RequestEncoder $requestEncoder;

    public function __construct(RequestEncoder $requestEncoder)
    {
        $this->requestEncoder = $requestEncoder;
    }

    /**
     * @param PhoneRepository $phoneRepository
     * @return JsonResponse
     */
    #[Route(path: '/phone', name: 'phones', methods: 'GET')]
    // TODO à paginer
    public function list(PhoneRepository $phoneRepository): JsonResponse
    {
        $jsonData = $this->requestEncoder->encodeArray("list", $phoneRepository->findAll());
        return new JsonResponse($jsonData, 200, [], true);
    }


    /**
     * @param Phone $phone
     * @return JsonResponse
     */
    #[Route(path: '/phone/{id}', name: 'phone_show', methods: 'GET')]
    public function show(Phone $phone): JsonResponse
    {
        $jsonData = $this->requestEncoder->encodeEntity("details", $phone);
        return new JsonResponse($jsonData, 200, [], true);
    }
}
