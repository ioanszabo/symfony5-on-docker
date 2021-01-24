<?php

namespace App\Tests\Component\RegRes;

use App\Component\Reqres\Repository\ReqresRepository;
use App\Component\Reqres\Repository\ReqResUserAdapter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ReqresRepositoryTest extends TestCase
{
    /**
     * @test
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function hasSourceAttribute(): void
    {
        $client = new MockHttpClient(
            [
                new MockResponse(
                    json_encode(
                        [
                            'data' =>
                                [
                                    [
                                        'id' => 1,
                                        'email' => 'george.bluth@reqres.in',
                                        'first_name' => 'George',
                                        'last_name' => 'Bluth',
                                        'avatar' => 'https://reqres.in/img/faces/1-image.jpg',
                                    ],
                                ],
                        ]
                    )
                ),
            ]
        );

        $reqresRepository = new ReqresRepository('https://reqres.in/api/', $client);
        $users = $reqresRepository->getUsers(1);
        $adaptedUser = new ReqResUserAdapter($users[0]);
        $this->assertTrue($adaptedUser->getSource() === ReqResUserAdapter::SOURCE);
    }
}
