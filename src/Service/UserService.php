<?php

namespace App\Service;

use App\Component\GoRest\Repository\GoRestRepository;
use App\Component\GoRest\Repository\GoRestUserAdapter;
use App\Component\Reqres\Repository\ReqresRepository;
use App\Component\Reqres\Repository\ReqResUserAdapter;
use App\Entity\GenericUser;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UserService
{
    private GoRestRepository $goRestRepository;
    private ReqresRepository $reqresRepository;

    public function __construct(GoRestRepository $goRestRepository, ReqresRepository $reqresRepository)
    {
        $this->goRestRepository = $goRestRepository;
        $this->reqresRepository = $reqresRepository;
    }

    /**
     * @return GenericUser[]
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getUsers(?string $source = null, ?string $sortBy = null, int $page = 1): array
    {
        $goRestUsers = [];
        $reqResUsers = [];
        if (!$source || $source === GoRestUserAdapter::SOURCE) {
            $goRestUsers = $this->goRestRepository->getUsers($page);
        }

        if (!$source || $source === ReqResUserAdapter::SOURCE) {
            $reqResUsers = $this->reqresRepository->getUsers($page);
        }

        $users = array_merge($goRestUsers, $reqResUsers);

        if ($sortBy) {
            return $this->sortUsers($users, $sortBy);
        }

        return $users;
    }

    /**
     * @param GenericUser[] $users
     * @return GenericUser[]
     */
    public function sortUsers(array $users, string $sortBy): array
    {
        list($by, $direction) = explode('_', $sortBy);

        if (!in_array($by, ['email', 'id', 'name', 'source']) || !in_array($direction, ['asc', 'desc'])) {
            return $users;
        }

        $method = trim('get' . ucfirst($by));

        $makeCompareFunction = function ($by) {
            return fn(GenericUser $a, GenericUser $b) => is_numeric($a->$by()) ? $a->$by() - $b->$by() :  strcmp($a->$by(), $b->$by());
        };

        $sortFunction = $makeCompareFunction($method);

        usort($users, $sortFunction);

        if ($direction === 'desc') {
            return array_reverse($users);
        }

        return $users;
    }
}
