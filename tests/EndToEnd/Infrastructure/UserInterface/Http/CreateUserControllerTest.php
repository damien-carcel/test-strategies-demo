<?php

namespace App\Tests\EndToEnd\Infrastructure\UserInterface\Http;

use App\Domain\Repository\UserRepository;
use App\Tests\EndToEnd\AbstractEndToEndTestCase;
use PHPUnit\Framework\Attributes\Test;

final class CreateUserControllerTest extends AbstractEndToEndTestCase
{
    #[Test]
    public function itCreatesAUser(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/users',
            parameters: ['email' => 'john.doe@email.com', 'firstname' => 'John', 'lastname' => 'Doe'],
        );

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(201);

        $storedUser = self::getService(UserRepository::class)->getByEmail('john.doe@email.com');
        self::assertSame('john.doe@email.com', $storedUser->email);
        self::assertSame('John', $storedUser->firstname);
        self::assertSame('Doe', $storedUser->lastname);
    }
}
