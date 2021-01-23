<?php

namespace App\Tests\Component\GoRest;

use App\Component\GoRest\Repository\GoRestRepository;
use App\Component\GoRest\Repository\GoRestUserAdapter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GoRestRepositoryTest extends TestCase
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
                                        'name' => 'Ioan Adrian Szabo',
                                        'email' => 'ioan.szabo@cucu.com',
                                        'gender' => 'male',
                                        'status' => 'active',
                                        'created_at' => '2021-01-23T03:50:03.841+05:30',
                                        'updated_at' => '2021-01-23T03:50:03.841+05:30',
                                    ],
                                ],
                        ]
                    )
                ),
            ]
        );

        $goResRepository = new GoRestRepository('https://gorest.co.in/public-api/', $client);
        $users = $goResRepository->getUsers(1);
        $this->assertTrue($users[0]->getSource() === GoRestUserAdapter::SOURCE);
    }
}
