<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\RequestDecoder;
use App\Service\EntityPersister;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/v1')]
class UserController extends AbstractController
{

    private EntityPersister $entityPersister;
    private RequestDecoder $requestDecoder;


    public function __construct(EntityPersister $entityPersister, RequestDecoder $requestDecoder)
    {
        $this->entityPersister = $entityPersister;
        $this->requestDecoder = $requestDecoder;
    }

    /**
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    #[Route(path: '/users', name: 'list_user', methods: 'GET')]
    // TODO Mettre en place une pagination
    public function list(UserRepository $userRepository)
    {
        $users = $userRepository->findBy(['client' => $this->getUser()]);

        return $this->json([
            'users' => $users,
        ], 200, [], [
            'groups' => ['list']
        ]);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    #[Route(path: '/user/{id}', name: 'details_user', methods: 'GET')]
    // TODO Add voter
    public function details(User $user): JsonResponse
    {
        return $this->json([
            'user' => $user,
        ], 200, [], [
            'groups' => ['details']
        ]);
    }

    /**
     * @param void
     * @return JsonResponse
     */
    #[Route(path: '/user', name: 'add_user', methods: 'POST')]
    public function add(): JsonResponse
    {
        $user = $this->requestDecoder->createUser($this->getUser());
        $this->entityPersister->update($user);
        return $this->json([
            "user" => $user
        ], 201, [],
            [
                'groups' => 'details'
            ]);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    #[Route(path: '/user/{id}', name: 'edit_user', methods: 'PUT')]
    public function edit(User $user): JsonResponse
    {
        $this->requestDecoder->updateUser($user);
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
    #[Route(path: '/user/{id}', name: 'remove_user', methods: 'DELETE')]
    public function remove(User $user)
    {
        $user = $this->entityPersister->delete($user);
        return $this->json([
            "response" => $user
        ], 204, []);
    }
}
