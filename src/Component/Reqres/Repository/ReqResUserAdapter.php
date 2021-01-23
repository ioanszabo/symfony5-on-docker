<?php

namespace App\Component\Reqres\Repository;

use App\Entity\GenericUser;

class ReqResUserAdapter implements GenericUser
{
    public const SOURCE = 'reqrest';
    private ReqResUserEntity $reqResUserEntity;

    public function __construct(ReqResUserEntity $reqResUserEntity)
    {
        $this->reqResUserEntity = $reqResUserEntity;
    }

    public function getEmail(): string
    {
        return $this->reqResUserEntity->getEmail();
    }

    public function getId(): int
    {
        return $this->reqResUserEntity->getId();
    }

    public function getName(): string
    {
        return $this->reqResUserEntity->getFirstName() . ' ' . $this->reqResUserEntity->getLastName();
    }

    public function getSource(): string
    {
        return self::SOURCE;
    }

    public function jsonSerialize()
    {
        return [
            'email' => $this->getEmail(),
            'id' => $this->getId(),
            'name' => $this->getName(),
            'source' => $this->getSource(),
        ];
    }
}
