<?php


namespace App\Controller;


use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminUtilityController extends AbstractController
{
    /**
     * @Route(
     *     "/admin/utility/users",
     *     methods="GET",
     *     name="admin_utility_users"
     * )
     * @param UserRepository $userRepository
     * @param Request $request
     * @return JsonResponse
     */
    public function getUsersApi(
        UserRepository $userRepository,
        Request $request
    ) {
        $users = $userRepository->findAllMatching(
            $request->query->get('query')
        );

        return $this->json([
            'users' => $users
        ], 200, [], ['groups' => ['main']]);
    }
}