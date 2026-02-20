<?php

namespace App\Domain\Exception;

final class UserAlreadyExists extends \DomainException
{
    public function __construct(string $email)
    {
        parent::__construct("User with email \"$email\" already exist.");
    }
}
