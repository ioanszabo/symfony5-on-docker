<?php

namespace App\Service;

use App\Component\GoRest\Repository\GoRestRepository;
use App\Component\GoRest\Repository\GoRestUserAdapter;
use App\Component\GoRest\Repository\GoRestUserEntity;
use App\Component\Reqres\Repository\ReqresRepository;
use App\Component\Reqres\Repository\ReqResUserAdapter;
use App\Component\Reqres\Repository\ReqResUserEntity;
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
        $users = $this->fetchUsers($source, $page);

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

        if ($this->isSortingNeeded($by, $direction)) {
            return $users;
        }

        $method = trim('get'.ucfirst($by));

        $makeCompareFunction = function ($by) {
            return fn(GenericUser $a, GenericUser $b) => is_numeric($a->$by()) ? $a->$by() - $b->$by() : strcmp(
                $a->$by(),
                $b->$by()
            );
        };

        $sortFunction = $makeCompareFunction($method);

        usort($users, $sortFunction);

        if ($direction === 'desc') {
            return array_reverse($users);
        }

        return $users;
    }

    /**
     * @param string|null $source
     * @param int $page
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function fetchUsers(?string $source, int $page): array
    {
        $goRestUsers = [];
        $reqResUsers = [];
        if (!$source || $source === GoRestUserAdapter::SOURCE) {
            $goRestUsers = $this->goRestRepository->getUsers($page);
            $goRestUsers = array_map(
                fn(GoRestUserEntity $goRestUserEntity) => new GoRestUserAdapter($goRestUserEntity),
                $goRestUsers
            );
        }

        if (!$source || $source === ReqResUserAdapter::SOURCE) {
            $reqResUsers = $this->reqresRepository->getUsers($page);
            $reqResUsers = array_map(
                fn(ReqResUserEntity $reqResUserEntity) => new ReqResUserAdapter($reqResUserEntity),
                $reqResUsers
            );
        }
        
        return array_merge($goRestUsers, $reqResUsers);
    }

    /**
     * @param string $by
     * @param string $direction
     * @return bool
     */
    public function isSortingNeeded(string $by, string $direction): bool
    {
        return !in_array($by, ['email', 'id', 'name', 'source']) || !in_array($direction, ['asc', 'desc']);
    }
}
