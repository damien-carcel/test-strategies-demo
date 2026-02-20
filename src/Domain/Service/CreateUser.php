<?php

namespace App\Domain\Service;

use App\Domain\Exception\UserAlreadyExists;
use App\Domain\Exception\UserNotFound;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;

final readonly class CreateUser
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function __invoke(string $email, string $firstname, string $lastname): void
    {
        if ($this->userAlreadyExists($email)) {
            throw new UserAlreadyExists($email);
        }

        $this->userRepository->save(new User($email, $firstname, $lastname));
    }

    private function userAlreadyExists(string $email): bool
    {
        try {
            $this->userRepository->getByEmail($email);
        } catch (UserNotFound) {
            return false;
        }

        return true;
    }
}
