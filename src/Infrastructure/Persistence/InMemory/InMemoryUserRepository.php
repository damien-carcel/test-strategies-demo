<?php

namespace App\Infrastructure\Persistence\InMemory;

use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;

final class InMemoryUserRepository implements UserRepository
{
    /** @var User[] */
    private array $users;

    public function save(User ...$users): void
    {
        foreach ($users as $user) {
            $this->users[] = clone $user;
        }
    }

    public function getByEmail(string $email): ?User
    {
        $user = array_first(array_filter(
            $this->users,
            static fn (User $user): bool => $user->email === $email,
        ));

        if (!$user instanceof User) {
            return null;
        }

        return $user;
    }
}
