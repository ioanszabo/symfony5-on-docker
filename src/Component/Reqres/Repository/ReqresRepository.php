<?php

namespace App\Component\Reqres\Repository;

use App\Entity\GenericUser;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ReqresRepository
{
    private string $apiUrl;
    private HttpClientInterface $client;

    public function __construct(string $apiUrl, HttpClientInterface $client)
    {
        $this->apiUrl = $apiUrl;
        $this->client = $client;
    }

    /**
     * @return GenericUser[]
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws TransportExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function getUsers(int $page): array
    {
        $response = $this->client->request(
            'GET',
            $this->apiUrl.'users?page=' . $page,
        );

        $users = $response->toArray()['data'];

        $reqresUsers = array_map(
            fn(array $user) => new ReqResUserEntity(
                $user['avatar'],
                $user['email'],
                $user['first_name'],
                $user['id'],
                $user['last_name'],
            ),
            $users
        );

        return array_map(
            fn(ReqResUserEntity $reqResUserEntity) => new ReqResUserAdapter($reqResUserEntity),
            $reqresUsers
        );
    }
}
