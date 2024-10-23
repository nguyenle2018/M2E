<?php

namespace App\DataFixtures;

use App\Entity\Candidature;
use App\Entity\Mission;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CandidatureFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $utilisateurs = $manager->getRepository(Utilisateur::class)->findAll();
        $missions = $manager->getRepository(Mission::class)->findAll();

        if (!$utilisateurs || !$missions) {
            return;  // Ensure there are users and missions to link to
        }

        for ($i = 0; $i < 50; $i++) {
            $candidature = new Candidature();
            $candidature->setUtilisateur($faker->randomElement($utilisateurs));  // Randomly assign a user
            $candidature->setMission($faker->randomElement($missions));  // Randomly assign a mission
            $candidature->setDateInscription($faker->dateTimeBetween('-100 days', 'now'));
            $candidature->setStatus($faker->randomElement(['En Attente', 'Accepté', 'Rejecté']));  // Random status

            $manager->persist($candidature);
        }

        $manager->flush();
    }
}
