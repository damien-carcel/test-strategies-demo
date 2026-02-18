<?php

namespace App\Entity;

use Symfony\Component\Uid\Uuid;

class User
{
    public private(set) Uuid $id {
        get => $this->id;
    }

    public function __construct(
        private(set) string $email {
            get => $this->email;
        },
        private(set) string $firstname {
            get => $this->firstname;
        },
        private(set) string $lastname {
            get => $this->lastname;
        },
    )
    {
        $this->id = Uuid::v7();
    }
}
