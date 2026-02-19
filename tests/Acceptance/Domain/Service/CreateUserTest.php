<?php

namespace App\Tests\Acceptance\Domain\Service;

use App\Domain\Exception\InvalidUser;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;
use App\Domain\Service\CreateUser;
use App\Tests\Acceptance\AbstractAcceptanceTestCase;
use PHPUnit\Framework\Attributes\Test;

final class CreateUserTest extends AbstractAcceptanceTestCase
{
    private CreateUser $createUser;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createUser = self::getService(CreateUser::class);
        $this->userRepository = self::getService(UserRepository::class);
    }

    #[Test]
    public function itCreatesAUser(): void
    {
        $userEmail = 'john.doe@email.com';
        $userFirstname = 'John';
        $userLastname = 'Doe';

        ($this->createUser)($userEmail, $userFirstname, $userLastname);

        $retrievedUser = $this->userRepository->getByEmail($userEmail);
        self::assertInstanceOf(User::class, $retrievedUser);
        self::assertSame($userEmail, $retrievedUser->email);
        self::assertSame($userFirstname, $retrievedUser->firstname);
        self::assertSame($userLastname, $retrievedUser->lastname);
    }

    #[Test]
    public function itThrowsAnExceptionIfItTriesToCreateAUserWithSomeEmptyValues(): void
    {
        try {
            ($this->createUser)('john.doe@email.com', '', '');
        } catch (\Throwable $exception) {
            self::assertInstanceOf(InvalidUser::class, $exception);
            self::assertNull($this->userRepository->getByEmail('john.doe@email.com'));

            return;
        }

        self::fail('An exception should have been thrown.');
    }
}
