<?php

namespace App\Entity;

use JsonSerializable;

class CombinedUser implements GenericUser, JsonSerializable
{
    private string $email;
    private int $id;
    private string $name;
    private string $source;

    public function __construct(string $email, int $id, string $name)
    {
        $this->email = $email;
        $this->id = $id;
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function jsonSerialize()
    {
        return [
            'email' => $this->email,
            'id' => $this->id,
            'name' => $this->name,
            'source' => $this->source,
        ];
    }
}
