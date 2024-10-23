<?php

namespace App\DataFixtures;

use App\Entity\Association;
use App\Entity\Mission;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class MissionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');


        // Assuming you have AssociationFixtures and they set references
        $associations = $manager->getRepository(Association::class)->findAll();
        if (!$associations) {
            // Normally you'd want some error handling or logging here
            return;
        }

        for ($i = 0; $i < 10; $i++) {
            $mission = new Mission();
            $mission->setNomMission($faker->randomElement(['Nettoyage de plage', 'Plantation d’arbres', 'Aide aux devoirs scolaires', 'Rénovation de locaux', 'Accompagnement de personnes handicapées', 'Sensibilisation à l’environnement',  'Soutien aux personnes âgées']));
            $mission->setDescription($faker->realText(200));
            $mission->setLieu($faker->city);
            $mission->setDate($faker->dateTimeBetween('-1 year', 'now'));
            $mission->setTypeMission($faker->randomElement(['Alphabétisation','Animaux', 'Arts et Culture', 'Éducation', 'Handicap', 'Social','Sport']));
            $mission->setCompetences($faker->words(3, true));
            $mission->setSiteInternet($faker->url);

            // Randomly assign an Association to a Mission
            $mission->setAssociation($faker->randomElement($associations));

            $manager->persist($mission);
        }

        $manager->flush();
    }
}
