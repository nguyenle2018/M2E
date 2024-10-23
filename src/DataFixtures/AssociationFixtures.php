<?php

namespace App\DataFixtures;

use App\Entity\Association;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AssociationFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $association = new Association();
            $association->setNom($faker->company);
            $association->setEmail($faker->companyEmail);
            $association->setPassword($this->passwordHasher->hashPassword($association, '123*')); // Example secure password
            $association->setSiteInternet($faker->optional()->url);
            $association->setTelephone($faker->phoneNumber);
            $association->setNomContact($faker->name);

            $manager->persist($association);
        }

        $manager->flush();
    }
}
