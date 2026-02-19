<?php

namespace App\Infrastructure\Persistence\Database;

use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
final class DatabaseUserRepository extends ServiceEntityRepository implements UserRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User ...$users): void
    {
        foreach ($users as $user) {
            $this->getEntityManager()->persist($user);
        }
        $this->getEntityManager()->flush();
    }

    public function getByEmail(string $email): ?User
    {
        $user = $this->findOneBy(['email' => $email]);

        if (!$user instanceof User) {
            return null;
        }

        return $user;
    }
}
