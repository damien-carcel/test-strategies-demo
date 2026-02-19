<?php

namespace App\Tests\Unit\Entity;

use App\Domain\Model\User;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class UserTest extends TestCase
{
    #[Test]
    public function itHasAnId(): void
    {
        $user = new User('john.doe@email.com', 'John', 'Doe');

        self::assertTrue(Uuid::isValid($user->id));
    }

    #[Test]
    public function itReturnsItsEmail(): void
    {
        $user = new User('john.doe@email.com', 'John', 'Doe');

        self::assertSame('john.doe@email.com', $user->email);
    }

    #[Test]
    public function itReturnsItsFirstname(): void
    {
        $user = new User('john.doe@email.com', 'John', 'Doe');

        self::assertSame('John', $user->firstname);
    }

    #[Test]
    public function itReturnsItsLastname(): void
    {
        $user = new User('john.doe@email.com', 'John', 'Doe');

        self::assertSame('Doe', $user->lastname);
    }
}
