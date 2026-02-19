<?php

namespace App\Domain\Service;

use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;

final readonly class CreateUser
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function __invoke(string $email, string $firstname, string $lastname): void
    {
        $this->userRepository->save(new User($email, $firstname, $lastname));
    }
}
