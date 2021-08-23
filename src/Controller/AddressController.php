<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\RequestDecoder;
use App\Service\EntityPersister;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/v1/user/{id}')]
class AddressController extends AbstractController
{
    private EntityPersister $entityPersister;
    private RequestDecoder $requestDecoder;

    public function __construct(EntityPersister $entityPersister, RequestDecoder $requestDecoder)
    {
        $this->entityPersister = $entityPersister;
        $this->requestDecoder = $requestDecoder;
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    #[Route(path: '/address', name: 'add_address', methods: 'POST')]
    public function add(User $user): JsonResponse
    {
        $this->requestDecoder->createAddress($user);
        $this->entityPersister->update($user);
        return $this->json([
            "user" => $user
        ], 201, [], [
            'groups' => ['details']
        ]);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    #[Route(path: '/address', name: 'edit', methods: 'PUT')]
    public function edit(User $user): JsonResponse
    {
        $this->requestDecoder->updateAddress($user);
        $this->entityPersister->update($user);
        return $this->json([
            "user" => $user
        ], 201, [], [
            'groups' => ['details']
        ]);
    }
}
