<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use App\Service\PaginationCollection;
use App\Service\RequestEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

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
     * @OA\Response(
     *     response=200,
     *     description="Returns the list Bilemo Phone",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Phone::class, groups={"list"}))
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
     * @OA\Tag(name="phone")
     * @Security(name="Bearer")
     *
     * @param Request $request
     * @param PaginationCollection $pagination
     * @return JsonResponse
     */
    #[Route(path: '/phones', name: 'phones', methods: 'GET')]
    public function list(Request $request, PaginationCollection $pagination): JsonResponse
    {
        $collection = $pagination->getPaginationResult(
            Phone::class,
            $request->attributes->get('_route'),
            $request->query->get('page', 1),
            PhoneRepository::PHONE_LIMIT,
            [],
            ['id' => 'ASC']);

        $jsonData = $this->requestEncoder->encodeEntity(["list"], $collection);
        return new JsonResponse($jsonData, 200, [], true);
    }


    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns the details of Bilemo Phone",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Phone::class, groups={"list"}))
     *     )
     * )
     * @OA\Parameter(
     *     name="id",
     *     in="query",
     *     description="The id of Bilemo phone",
     *     @OA\Schema(type="int")
     * )
     * @OA\Tag(name="phone")
     * @Security(name="Bearer")
     *
     * @param Phone $phone
     * @return JsonResponse
     */
    #[Route(path: '/phone/{id}', name: 'phone_show', methods: 'GET')]
    public function show(Phone $phone): JsonResponse
    {
        $jsonData = $this->requestEncoder->encodeEntity(["details"], $phone);
        return new JsonResponse($jsonData, 200, [], true);
    }
}
