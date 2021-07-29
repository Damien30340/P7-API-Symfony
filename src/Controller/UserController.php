<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Services\Manager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class UserController extends AbstractController
{
    private Manager $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    public function list(UserRepository $userRepository): Response
    {
        $userList = $userRepository->findAll();

        return $this->json([
            'userList' => $userList,
        ], 200, [], [
            'groups' => ['list']
        ]);
    }

    public function details($id, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(['id'=> $id]);

        return $this->json([
            'user' => $user,
        ], 200, [], [
            'groups' => ['details']
        ]);
    }

    public function add(Request $request): Response
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $data = $request->getContent();

        $user = $serializer->deserialize($data, User::class, 'json');

        $this->manager->update($user);

        return $this->json([
            "user" => $user
        ], 201);
    }

    public function edit($id, UserRepository $userRepository, Request $request): Response
    {
        $user = $userRepository->findOneBy(['id' => $id]);

        return $this->json([
            "user" => $user
        ], 200, [], [
            'groups' => ['details']
        ]);
    }
}
