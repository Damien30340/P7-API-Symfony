<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\RequestDecoder;
use App\Service\EntityPersister;
use App\Service\RequestEncoder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

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
     * @OA\Response(
     *     response=201,
     *     description="Return response or error validator",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=User::class, groups={"details"}))
     *     )
     * )
     * @OA\Parameter(
     *     name="id",
     *     in="query",
     *     description="The id of your user",
     *     @OA\Schema(type="int")
     * )
     * @OA\Tag(name="user")
     * @Security(name="Bearer")
     *
     * @param User $user
     * @return JsonResponse
     */
    #[Route(path: '/address', name: 'address_create', methods: 'POST'), IsGranted("user_view", "user", "Accès refusé !")]
    public function create(User $user): JsonResponse
    {
        $this->requestDecoder->createAddress($user);
        $this->entityPersister->update($user);
        $jsonData = $this->requestEncoder->encodeEntity(["details"], $user);
        return new JsonResponse($jsonData, 201, [], true);
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Return address update or error validator"
     * )
     * @OA\Parameter(
     *     name="id",
     *     in="query",
     *     description="The id of your user",
     *     @OA\Schema(type="int")
     * )
     * @OA\Tag(name="user")
     * @Security(name="Bearer")
     *
     * @param User $user
     * @return JsonResponse
     */
    #[Route(path: '/address', name: 'address_update', methods: 'PUT'), IsGranted("user_view", "user", "Accès refusé !")]
    public function update(User $user): JsonResponse
    {
        $this->requestDecoder->updateAddress($user);
        $this->entityPersister->update($user);
        $jsonData = $this->requestEncoder->encodeEntity(["details"], $user);
        return new JsonResponse($jsonData, 200, [], true);
    }

    /**
     * @OA\Response(
     *     response=204,
     *     description="Return response or error 404"
     * )
     * @OA\Parameter(
     *     name="id",
     *     in="query",
     *     description="The id of your user",
     *     @OA\Schema(type="int")
     * )
     * @OA\Tag(name="user")
     * @Security(name="Bearer")
     *
     * @param User $user
     * @return JsonResponse
     */
    #[Route(path: '/address', name: 'address_delete', methods: 'DELETE'), IsGranted("user_view", "user", "Accès refusé !")]
    public function delete(User $user): JsonResponse
    {
        $this->entityPersister->delete($user->getAddress()->first());
        return new JsonResponse(null, 204, [], true);
    }
}
