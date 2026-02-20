<?php

namespace App\Domain\Exception;

final class UserNotFound extends \DomainException
{
    public function __construct(string $email)
    {
        parent::__construct("Could not find a user with email \"$email\".");
    }
}
