<?php

namespace App\Tests\Controller;

use App\Component\GoRest\Repository\GoRestUserAdapter;
use App\Component\Reqres\Repository\ReqResUserAdapter;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function getAvailableSources(): array
    {
        return [
            [GoRestUserAdapter::SOURCE],
            [ReqResUserAdapter::SOURCE],
        ];
    }

    /**
     * @test
     */
    public function getUsers(): void
    {
        $client = static::createClient();
        $client->request('GET', '/users');

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
    }

    /**
     * @test
     * @dataProvider getAvailableSources
     */
    public function getUsersBySource(string $source): void
    {
        $client = static::createClient();
        $client->request('GET', '/users', ['source' => $source]);
        $users = json_decode($client->getResponse()->getContent());
        $goRestUsers = array_filter($users, fn(stdClass $user) => $user->source === $source);
        $this->assertSameSize($users, $goRestUsers);
    }
}
