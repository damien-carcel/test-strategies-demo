<?php

namespace App\Domain\Model;

use App\Domain\Exception\InvalidUser;
use Symfony\Component\Uid\Uuid;

class User
{
    public private(set) Uuid $id {
        get => $this->id;
    }
    public private(set) string $email {
        get => $this->email;
    }
    public private(set) string $firstname {
        get => $this->firstname;
    }
    public private(set) string $lastname {
        get => $this->lastname;
    }

    public function __construct(string $email, string $firstname, string $lastname)
    {
        $emptyFields = array_filter(
            ['email' => $email, 'firstname' => $firstname, 'lastname' => $lastname],
            static fn (string $field): bool => '' === $field,
            ARRAY_FILTER_USE_BOTH,
        );
        if (!empty($emptyFields)) {
            throw new InvalidUser(...array_keys($emptyFields));
        }

        $this->id = Uuid::v7();
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
    }
}
