<?php

namespace App\Domain\Repository;

use App\Domain\Exception\UserNotFound;
use App\Domain\Model\User;

interface UserRepository
{
    public function save(User ...$users): void;

    /**
     * @throws UserNotFound
     */
    public function getByEmail(string $email): User;
}
