<?php

namespace App\Entity;

interface GenericUser
{
    public function getEmail(): string;

    public function getId(): int;

    public function getName(): string;

    public function getSource(): string;

    public function jsonSerialize();
}
