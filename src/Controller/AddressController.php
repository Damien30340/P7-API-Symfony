<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\Voter\UserVoter;
use App\Service\RequestDecoder;
use App\Service\EntityPersister;
use App\Service\RequestEncoder;
use JMS\Serializer\Exception\NonFloatCastableTypeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/v1/user/{id}')]
class AddressController extends AbstractController
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
     * @param User $user
     * @return JsonResponse
     */
    #[Route(path: '/address', name: 'address_create', methods: 'POST')]
    public function create(User $user): JsonResponse
    {
        $this->denyAccessUnlessGranted(UserVoter::USER_UPDATE, $user, 'Accès refusé !');
        $this->requestDecoder->createAddress($user);
        $this->entityPersister->update($user);
        $jsonData = $this->requestEncoder->encodeEntity("details", $user);
        return new JsonResponse($jsonData, 201, [], true);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    #[Route(path: '/address', name: 'address_update', methods: 'PUT')]
    public function update(User $user): JsonResponse
    {
        $this->denyAccessUnlessGranted(UserVoter::USER_UPDATE, $user, 'Accès refusé !');
        $this->requestDecoder->updateAddress($user);
        $this->entityPersister->update($user);
        $jsonData = $this->requestEncoder->encodeEntity("details", $user);
        return new JsonResponse($jsonData, 201, [], true);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    #[Route(path: '/address', name: 'address_delete', methods: 'DELETE')]
    public function delete(User $user): JsonResponse
    {
        $this->denyAccessUnlessGranted(UserVoter::USER_DELETE, $user, 'Accès refusé !');
        $this->entityPersister->delete($user->getAddress()->first());
        return new JsonResponse(null, 204, [], true);
    }
}
