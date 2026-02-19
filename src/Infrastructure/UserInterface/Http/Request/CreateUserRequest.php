<?php

namespace App\Infrastructure\UserInterface\Http\Request;

final readonly class CreateUserRequest
{
    public function __construct(public string $email, public string $firstname, public string $lastname)
    {
    }
}
