<?php

namespace App\Tests\Unit\Service;

use App\Domain\Exception\InvalidUser;
use App\Domain\Exception\UserAlreadyExists;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;
use App\Domain\Service\CreateUser;
use App\Infrastructure\Persistence\InMemory\InMemoryUserRepository;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * For each tested scenario, we have two tests:
 * - One that uses the in-memory adapters, exactly like the acceptance tests in
 *   App\Tests\Acceptance\Domain\Service\CreateUserTest
 * - One that uses mocks instead.
 */
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
        $userRepository = new InMemoryUserRepository();
        $createUser = new CreateUser($userRepository);

        try {
            ($createUser)('john.doe@email.com', '', '');
        } catch (\Throwable $exception) {
            self::assertInstanceOf(InvalidUser::class, $exception);

            return;
        }

        self::fail('An exception should have been thrown.');
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

    #[Test]
    public function itThrowsAnExceptionIfItTriesToCreateAUserWithTheSameEmailAsAnAlreadyExistingUserWithInMemoryAdapters(): void
    {
        $userRepository = new InMemoryUserRepository();
        $createUser = new CreateUser($userRepository);
        ($createUser)('jane.doe@email.com', 'Jane', 'Doe');

        try {
            ($createUser)('jane.doe@email.com', 'John', 'Doe');
        } catch (\Throwable $exception) {
            self::assertInstanceOf(UserAlreadyExists::class, $exception);
            self::assertSame('User with email "jane.doe@email.com" already exist.', $exception->getMessage());

            $existingUser = $userRepository->getByEmail('jane.doe@email.com');
            self::assertSame('Jane', $existingUser->firstname);

            return;
        }

        self::fail('An exception should have been thrown.');
    }

    #[Test]
    public function itThrowsAnExceptionIfItTriesToCreateAUserWithTheSameEmailAsAnAlreadyExistingUserWithMocks(): void
    {
        $userRepository = $this->createMock(UserRepository::class);
        $createUser = new CreateUser($userRepository);

        $userRepository->expects($this->never())->method('save');
        $userRepository
            ->expects($this->once())
            ->method('getByEmail')
            ->with('jane.doe@email.com')
            ->willReturn(new User('jane.doe@email.com', 'Jane', 'Doe'));

        $this->expectException(UserAlreadyExists::class);
        $this->expectExceptionMessage('User with email "jane.doe@email.com" already exist.');
        ($createUser)('jane.doe@email.com', 'John', 'Doe');
    }
}
