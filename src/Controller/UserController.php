<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\EncoderJson;
use App\Service\Manager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


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

    public function add(EncoderJson $encoderJson, Request $request): Response
    {
        $user = $encoderJson->decodeUserAdd($request->getContent());
        $user = $this->manager->update($user);
        return $this->json([
            "response" => $user
        ], 201, [],
        [
            'groups' => 'details'
        ]);
    }

    public function edit($id, UserRepository $userRepository, EncoderJson $encoderJson, Request $request): Response
    {
        $user = $userRepository->findOneBy(['id' => $id]);
        $encoderJson->decodeUserUpdate($request->getContent(), $user);
        $user = $this->manager->update($user);
        return $this->json([
            "user" => $user
        ], 201, [], [
            'groups' => ['details']
        ]);
    }

    public function remove($id, UserRepository $userRepository){
        $user = $userRepository->findOneBy(['id' => $id]);
        $user = $this->manager->delete($user);
        return $this->json([
            "response" => $user
        ], 204, []);
    }
}
