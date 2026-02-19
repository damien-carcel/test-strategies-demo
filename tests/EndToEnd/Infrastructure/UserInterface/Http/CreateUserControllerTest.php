<?php

namespace App\Tests\EndToEnd\Infrastructure\UserInterface\Http;

use App\Domain\Model\User;
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
        self::assertInstanceOf(User::class, self::getService(UserRepository::class)->getByEmail('john.doe@email.com'));
    }
}
