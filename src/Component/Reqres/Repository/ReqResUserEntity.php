<?php

namespace App\Component\Reqres\Repository;

class ReqResUserEntity
{
    private string $avatar;
    private string $email;
    private string $firstName;
    private int $id;
    private string $lastName;

    public function __construct(string $avatar, string $email, string $firstName, int $id, string $lastName)
    {
        $this->avatar = $avatar;
        $this->email = $email;
        $this->firstName = $firstName;
        $this->id = $id;
        $this->lastName = $lastName;
    }

    public function getAvatar(): string
    {
        return $this->avatar;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }
}
