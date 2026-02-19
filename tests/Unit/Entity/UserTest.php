<?php

namespace App\Tests\Unit\Entity;

use App\Domain\Exception\InvalidUser;
use App\Domain\Model\User;
use PHPUnit\Framework\Attributes\DataProvider;
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

    #[Test]
    #[DataProvider('emptyProperties')]
    public function itCannotBeCreatedWithEmptyProperties(
        string $email,
        string $firstname,
        string $lastname,
        string $expectedExceptionMessage,
    ): void {
        $this->expectException(InvalidUser::class);
        $this->expectExceptionMessage($expectedExceptionMessage);

        new User($email, $firstname, $lastname);
    }

    /**
     * @return iterable<string, array{
     *     email: string,
     *     firstname: string,
     *     lastname: string,
     *     expectedExceptionMessage: string,
     * }>
     */
    public static function emptyProperties(): iterable
    {
        yield 'empty email' => [
            'email' => '',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'expectedExceptionMessage' => "The user's email cannot be empty.",
        ];

        yield 'empty firstname' => [
            'email' => 'john.doe@email.com',
            'firstname' => '',
            'lastname' => 'Doe',
            'expectedExceptionMessage' => "The user's firstname cannot be empty.",
        ];

        yield 'empty lastname' => [
            'email' => 'john.doe@email.com',
            'firstname' => 'John',
            'lastname' => '',
            'expectedExceptionMessage' => "The user's lastname cannot be empty.",
        ];

        yield 'multiple empty properties' => [
            'email' => 'john.doe@email.com',
            'firstname' => '',
            'lastname' => '',
            'expectedExceptionMessage' => "The user's firstname and lastname cannot be empty.",
        ];

        yield 'all properties empty' => [
            'email' => '',
            'firstname' => '',
            'lastname' => '',
            'expectedExceptionMessage' => "The user's email, firstname and lastname cannot be empty.",
        ];
    }
}
