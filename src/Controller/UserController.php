<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\PaginationCollection;
use App\Service\RequestDecoder;
use App\Service\EntityPersister;
use App\Service\RequestEncoder;
use App\Service\Validator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

#[Route(path: '/api/v1')]
class UserController extends AbstractController
{
    private EntityPersister $entityPersister;
    private RequestDecoder $requestDecoder;
    private RequestEncoder $requestEncoder;
    private Validator $validator;

    public function __construct(EntityPersister $entityPersister, RequestDecoder $requestDecoder, RequestEncoder $requestEncoder, Validator $validator)
    {
        $this->entityPersister = $entityPersister;
        $this->requestDecoder = $requestDecoder;
        $this->requestEncoder = $requestEncoder;
        $this->validator = $validator;
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns the list of your users",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=User::class, groups={"list"}))
     *     )
     * )
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The page number",
     *     @OA\Schema(type="int", default="1")
     * )
     * @OA\Parameter(
     *     name="limit",
     *     in="query",
     *     description="The limit items of page",
     *     @OA\Schema(type="int", default="10")
     * )
     * @OA\Tag(name="users")
     * @Security(name="Bearer")
     *
     * @param Request $request
     * @param PaginationCollection $pagination
     * @return JsonResponse
     */
    #[Route(path: '/users', name: 'users', methods: 'GET')]
    public function list(Request $request, PaginationCollection $pagination): JsonResponse
    {
        $collection = $pagination->getPaginationResult(
            User::class,
            $request->attributes->get('_route'),
            $request->query->get('page', 1),
            UserRepository::USER_LIMIT,
            ['client' => $this->getUser()],
            ['id' => 'ASC']);

        $jsonData = $this->requestEncoder->encodeEntity(["list"], $collection);
        return new JsonResponse($jsonData, 200, [], true);
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Return details of your user",
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
    #[Route(path: '/user/{id}', name: 'user_show', methods: 'GET'), IsGranted("user_view", "user", "Accès refusé !")]
    public function show(User $user): JsonResponse
    {
        $jsonData = $this->requestEncoder->encodeEntity(["details"],  $user);
        return new JsonResponse($jsonData, 200, [], true);
    }

    /**
     * @OA\Response(
     *     response=201,
     *     description="Return new user or error validator",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=User::class, groups={"details"}))
     *     )
     * )
     * @OA\Tag(name="user")
     * @Security(name="Bearer")
     *
     * @param void
     * @return JsonResponse
     */
    #[Route(path: '/user', name: 'user_create', methods: 'POST')]
    public function create(): JsonResponse
    {
            $user = $this->requestDecoder->createUser($this->getUser());
            $errors = $this->validator->validate($user);
                if($errors){
                    return $errors;
                }
            $this->entityPersister->update($user);
            $jsonData = $this->requestEncoder->encodeEntity(["details"], $user);
            return new JsonResponse($jsonData, 201, [], true);
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Return user update or error validator"
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
    #[Route(path: '/user/{id}', name: 'user_update', methods: 'PUT'), IsGranted("user_view", "user", "Accès refusé !")]
    public function update(User $user): JsonResponse
    {
        $this->requestDecoder->updateUser($user);
        $errors = $this->validator->validate($user);
            if($errors){
                return $errors;
            }
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
    #[Route(path: '/user/{id}', name: 'user_delete', methods: 'DELETE'), IsGranted("user_view", "user", "Accès refusé !")]
    public function delete(User $user): JsonResponse
    {
        $this->entityPersister->delete($user);
        return new JsonResponse(null, 204, [], true);
    }

    //TODO TACHE A FAIRE :
    //TODO mise en cache /
    //TODO Annotation login
}
