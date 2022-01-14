<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user', name: 'user_', methods: 'POST')]
class UserController extends AbstractController
{

    public function __construct(private UserRepository $userRepository) {}

    #[Route('/new', name: 'new', methods: 'POST')]
    public function add(Request $request): JsonResponse
    {

        $data = json_decode($request->getContent(), true);

        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $email = $data['email'];
        $password = $data['password'];

        if (empty($firstname) || empty($lastname) || empty($email) || empty($password))
        {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->userRepository->saveUser($firstname, $lastname, $email, $password);

        return new JsonResponse(['status' => 'User created!'], Response::HTTP_CREATED);
    }

    #[Route('/{id}/show', name: 'get_one_user', methods: 'GET')]
    public function get($id): JsonResponse
    {
        $user = $this->userRepository->findOneBy(['id' => $id]);

        $data = [
            'id' => $user->getId(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'email' => $user->getEmail(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/all', name: 'get_all_customers', methods: 'GET')]
    public function getAll(): JsonResponse
    {
        $users = $this->userRepository->findAll();
        $data = [];

        foreach ($users as $user)
        {
            $data[] = [
                'id' => $user->getId(),
                'firstname' => $user->getFirstname(),
                'lastname' => $user->getLastname(),
                'email' => $user->getEmail(),
            ];
        }


        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'update_user', methods: 'PUT')]
    public function update($id, Request $request): JsonResponse
    {
        $user = $this->userRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        empty($data['firstname']) ? true : $user->setFirstname($data['firstname']);
        empty($data['lastname']) ? true : $user->setLastname($data['lastname']);
        empty($data['email']) ? true : $user->setEmail($data['email']);
        empty($data['password']) ? true : $user->setPassword($data['password']);

        $updatedUser = $this->userRepository->updateUser($user);

        return new JsonResponse($updatedUser->toArray(), Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'delete_user', methods: 'DELETE')]
    public function delete($id): JsonResponse
    {
        $user = $this->userRepository->findOneBy(['id' => $id]);

        $this->userRepository->removeUser($user);

        return new JsonResponse(['status' => 'Customer deleted'], Response::HTTP_NO_CONTENT);
    }

}
