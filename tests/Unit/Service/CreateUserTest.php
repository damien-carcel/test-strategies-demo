<?php

namespace App\Tests\Unit\Service;

use App\Domain\Exception\InvalidUser;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;
use App\Domain\Service\CreateUser;
use App\Infrastructure\Persistence\InMemory\InMemoryUserRepository;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class CreateUserTest extends TestCase
{
    #[Test]
    public function itCreatesAUserWithInMemoryAdapters(): void
    {
        $userRepository = new InMemoryUserRepository();
        $createUser = new CreateUser($userRepository);

        $userEmail = 'john.doe@email.com';
        $userFirstname = 'John';
        $userLastname = 'Doe';

        ($createUser)($userEmail, $userFirstname, $userLastname);

        $retrievedUser = $userRepository->getByEmail($userEmail);
        self::assertInstanceOf(User::class, $retrievedUser);
        self::assertSame($userEmail, $retrievedUser->email);
        self::assertSame($userFirstname, $retrievedUser->firstname);
        self::assertSame($userLastname, $retrievedUser->lastname);
    }

    #[Test]
    public function itCreatesAUserWithMocks(): void
    {
        $userRepository = $this->createMock(UserRepository::class);
        $createUser = new CreateUser($userRepository);

        $userEmail = 'john.doe@email.com';
        $userFirstname = 'John';
        $userLastname = 'Doe';

        $userRepository
            ->expects($this->once())
            ->method('save')
            ->with(self::callback(
                static fn (User $user): bool => $user->email === $userEmail
                    && $user->firstname === $userFirstname
                    && $user->lastname === $userLastname,
            ));

        ($createUser)($userEmail, $userFirstname, $userLastname);
    }

    #[Test]
    public function itThrowsAnExceptionIfItTriesToCreateAUserWithSomeEmptyValuesWithInMemoryAdapters(): void
    {
        $userRepository = $this->createMock(UserRepository::class);
        $createUser = new CreateUser($userRepository);

        try {
            ($createUser)('john.doe@email.com', '', '');
        } catch (\Throwable $exception) {
            self::assertInstanceOf(InvalidUser::class, $exception);
            self::assertNull($userRepository->getByEmail('john.doe@email.com'));

            return;
        }
    }

    #[Test]
    public function itThrowsAnExceptionIfItTriesToCreateAUserWithSomeEmptyValuesWithMocks(): void
    {
        $userRepository = $this->createMock(UserRepository::class);
        $createUser = new CreateUser($userRepository);

        $userRepository->expects($this->never())->method('save');

        $this->expectException(InvalidUser::class);
        ($createUser)('john.doe@email.com', '', '');
    }
}
