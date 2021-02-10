<?php


namespace App\Controller;


use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AdminUtilityController extends AbstractController
{
    /**
     * @Route(
     *     "/admin/utility/users",
     *     methods="GET"
     * )
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function getUsersApi(UserRepository $userRepository)
    {
        $users = $userRepository->findAllEmailAlphabetical();

        return $this->json([
            'users' => $users
        ], 200, [], ['groups' => ['main']]);
    }
}