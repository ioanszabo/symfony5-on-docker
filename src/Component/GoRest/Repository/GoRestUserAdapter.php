<?php

namespace App\Component\GoRest\Repository;

use App\Entity\GenericUser;

class GoRestUserAdapter implements GenericUser
{
    public const SOURCE = 'gorest';
    private GoRestUserEntity $goRestUserEntity;

    public function __construct(GoRestUserEntity $goRestUserEntity)
    {
        $this->goRestUserEntity = $goRestUserEntity;
    }

    public function getEmail(): string
    {
        return $this->goRestUserEntity->getEmail();
    }

    public function getId(): int
    {
        return $this->goRestUserEntity->getId();
    }

    public function getName(): string
    {
        return $this->goRestUserEntity->getName();
    }

    public function getSource(): string
    {
        return self::SOURCE;
    }

    public function jsonSerialize()
    {
        return [
            'email' => $this->goRestUserEntity->getEmail(),
            'id' => $this->goRestUserEntity->getId(),
            'name' => $this->goRestUserEntity->getName(),
            'source' => self::SOURCE,
        ];
    }
}
