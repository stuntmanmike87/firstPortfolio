<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
// use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher/* ,
        private readonly SluggerInterface $slugger */
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $newUser = new User();

        $newUser->setUsername('User');
        $newUser->setPassword($this->hasher->hashPassword($newUser, 'azerty'));
        $newUser->setEmail('user@demo.fr');
        $newUser->setIsVerified(true);

        $manager->persist($newUser);

        $newUser = new User();

        $newUser->setUsername('Admin');
        $newUser->setPassword($this->hasher->hashPassword($newUser, 'azerty'));
        $newUser->setEmail('admin@demo.fr');
        $newUser->setIsVerified(true);
        $newUser->setRoles(['ROLE_ADMIN']);
        $this->setReference('Admin', $newUser);

        $manager->persist($newUser);

        $manager->flush();
    }
}
