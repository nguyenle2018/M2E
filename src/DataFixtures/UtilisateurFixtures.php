<?php

namespace App\DataFixtures;

use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UtilisateurFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 20; $i++) {
            $utilisateur = new Utilisateur();
            $utilisateur->setNom($faker->lastName);
            $utilisateur->setPrenom($faker->firstName);
            $utilisateur->setEmail($faker->email);
            $utilisateur->setPassword($this->passwordHasher->hashPassword($utilisateur, '1234'));
            $utilisateur->setTelephone($faker->phoneNumber);
            $utilisateur->setAdresse($faker->address);
            $utilisateur->setCodePostal($faker->postcode);
            $utilisateur->setVille($faker->city);
            $utilisateur->setAnneeNaissance($faker->year);
            $utilisateur->setRoles(['ROLE_USER']);

            $manager->persist($utilisateur);
        }
        $manager->flush();
    }
}
