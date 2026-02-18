<?php

namespace App\Tests\Integration\Integration;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\Integration\AbstractIntegrationTestCase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

final class UserRepositoryTest extends AbstractIntegrationTestCase
{
    private readonly UserRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = self::getService(UserRepository::class);
    }

    #[Test]
    #[Group('with-in-memory-adapters')]
    #[Group('with-production-adapters')]
    public function itSavesAUser(): void
    {
        $user = new User('john.doe@email.com', 'John', 'Doe');

        $this->repository->save($user);

        $this->expectNotToPerformAssertions();
    }

    #[Test]
    #[Group('with-in-memory-adapters')]
    #[Group('with-production-adapters')]
    public function itGetsAUserFromItsEmail(): void
    {
        $user1 = new User('john.doe@email.com', 'John', 'Doe');
        $user2 = new User('jane.doe@email.com', 'Jane', 'Doe');
        $this->repository->save($user1, $user2);

        $result = $this->repository->getByEmail('john.doe@email.com');

        self::assertEquals($user1, $result);
    }

    #[Test]
    #[Group('with-in-memory-adapters')]
    #[Group('with-production-adapters')]
    public function itReturnsNullIfTheUserDoesNotExist(): void
    {
        $user = new User('john.doe@email.com', 'John', 'Doe');
        $this->repository->save($user);

        $user = $this->repository->getByEmail('jane.doe@email.com');

        self::assertNull($user);
    }
}
