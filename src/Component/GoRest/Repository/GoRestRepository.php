<?php

namespace App\Component\GoRest\Repository;

use App\Entity\GenericUser;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GoRestRepository
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

        $goRestUsers = array_map(
            fn(array $user) => new GoRestUserEntity(
                $user['id'],
                $user['name'],
                $user['email'],
                $user['gender'],
                $user['status'],
                $user['created_at'],
                $user['updated_at']
            ),
            $users
        );

        return array_map(
            fn(GoRestUserEntity $goRestUserEntity) => new GoRestUserAdapter($goRestUserEntity),
            $goRestUsers
        );
    }
}
