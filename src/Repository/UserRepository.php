<?php

namespace App\Repository;

use App\Entity\User;

interface UserRepository
{
    public function save(User ...$users): void;

    public function getByEmail(string $email): ?User;
}
