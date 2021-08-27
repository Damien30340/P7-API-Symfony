<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\Voter\UserVoter;
use App\Service\RequestDecoder;
use App\Service\EntityPersister;
use App\Service\RequestEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/v1')]
class UserController extends AbstractController
{
    private EntityPersister $entityPersister;
    private RequestDecoder $requestDecoder;
    private RequestEncoder $requestEncoder;


    public function __construct(EntityPersister $entityPersister, RequestDecoder $requestDecoder, RequestEncoder $requestEncoder)
    {
        $this->entityPersister = $entityPersister;
        $this->requestDecoder = $requestDecoder;
        $this->requestEncoder = $requestEncoder;
    }

    /**
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    #[Route(path: '/users', name: 'users', methods: 'GET')]
    // TODO Mettre en place une pagination
    public function list(UserRepository $userRepository): JsonResponse
    {
        $jsonData = $this->requestEncoder->encodeArray("list", $userRepository->findBy(['client' => $this->getUser()]));
        return new JsonResponse($jsonData, 200, [], true);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    #[Route(path: '/user/{id}', name: 'user_show', methods: 'GET')]
    public function show(User $user): JsonResponse
    {
        $this->denyAccessUnlessGranted(UserVoter::USER_VIEW, $user, 'Accès refusé !');
        $jsonData = $this->requestEncoder->encodeEntity("details",  $user);
        return new JsonResponse($jsonData, 200, [], true);
    }

    /**
     * @param void
     * @return JsonResponse
     */
    #[Route(path: '/user', name: 'user_create', methods: 'POST')]
    public function create(): JsonResponse
    {
            $user = $this->requestDecoder->createUser($this->getUser());
            $this->entityPersister->update($user);
            $jsonData = $this->requestEncoder->encodeEntity("details", $user);
            return new JsonResponse($jsonData, 201, [], true);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    #[Route(path: '/user/{id}', name: 'user_update', methods: 'PUT')]
    public function update(User $user): JsonResponse
    {
        $this->denyAccessUnlessGranted(UserVoter::USER_UPDATE, $user, 'Accès refusé !');
        $this->requestDecoder->updateUser($user);
        $this->entityPersister->update($user);
        $jsonData = $this->requestEncoder->encodeEntity("details", $user);
        return new JsonResponse($jsonData, 201, [], true);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    #[Route(path: '/user/{id}', name: 'user_delete', methods: 'DELETE')]
    public function delete(User $user): JsonResponse
    {
        $this->denyAccessUnlessGranted(UserVoter::USER_DELETE, $user, 'Accès refusé !');
        $this->entityPersister->delete($user);
        return new JsonResponse(null, 204, [], true);
    }
}
