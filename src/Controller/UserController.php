<?php

namespace App\Controller;

use App\Entity\GenericUser;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UserController extends AbstractController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
       $this->userService = $userService;
    }

    /**
     * @Route("/users", name="list-of-users", methods={"GET"})
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     *
     */
    public function getUsers(Request $request): JsonResponse
    {
        $source = $request->query->get('source');
        $sortBy = $request->query->get('sort_by');
        $page = $request->query->get('page');
        $users = $this->userService->getUsers($source, $sortBy, !$page ? 1 : $page);

        return new JsonResponse(array_map(fn(GenericUser $user) => $user->jsonSerialize(), $users));
    }
}
