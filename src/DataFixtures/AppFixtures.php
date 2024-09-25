<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Movie;
use App\Entity\Actor;
use App\Entity\Category;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Initialisation de Faker avec les providers cinéma
        $faker = Factory::create();
        $faker->addProvider(new \Xylis\FakerCinema\Provider\Movie($faker));
        $faker->addProvider(new \Xylis\FakerCinema\Provider\Person($faker));

        // Définition des catégories spécifiques
        $categoryTitles = ['Thriller', 'Drame', 'Comédie', 'Fantastique'];
        $categories = [];

        // Générer des catégories spécifiques
        foreach ($categoryTitles as $title) {
            $category = new Category();
            $category->setTitle($title);
            $manager->persist($category);
            $categories[] = $category;
        }

        // Générer des acteurs
        $actors = [];
        for ($i = 0; $i < 20; $i++) {
            $actor = new Actor();
            $actor->setLastname($faker->lastName()) // Utilise le provider Person pour obtenir un nom d'acteur réaliste
                   ->setFirstname($faker->firstName())
                   ->setDob($faker->dateTimeBetween('-70 years', '-20 years'))
                   ->setMedia($faker->imageUrl(200, 300, 'people')) // Générer une image fictive pour les acteurs
                   ->setNationality($faker->country())
                   ->setGender($faker->randomElement(['M', 'F']))
                   ->setAwards($faker->numberBetween(0, 100));
            $manager->persist($actor);
            $actors[] = $actor;
        }

        // Générer des films et les associer avec des acteurs et des catégories
        for ($i = 0; $i < 20; $i++) {
            $movie = new Movie();
            $movie->setTitle($faker->movie()) // Titre réaliste de film
                  ->setReleaseDate($faker->dateTimeBetween('-30 years', 'now'))
                  ->setDirector($faker->director()) // Réalisateur réaliste
                  ->setDescription($faker->overview()) // Synopsis réaliste
                  ->setMedia($faker->imageUrl(400, 600, 'movies')) // Générer une image fictive pour les films
                  ->setEntries($faker->numberBetween(100000, 1000000));

            $movie->setRating($this->generateRandomRating());

            // Associer des acteurs aléatoires
            for ($j = 0; $j < mt_rand(1, 3); $j++) {
                $movie->addActor($actors[array_rand($actors)]);
            }

            // Associer des catégories aléatoires
            for ($j = 0; $j < mt_rand(1, 2); $j++) {
                $movie->addCategory($categories[array_rand($categories)]);
            }

            $manager->persist($movie);
        }

        // Enregistrer les modifications dans la base de données
        $manager->flush();
    }

     private function generateRandomRating(): float
    {
        // Générer un float aléatoire entre 0 et 5 avec deux décimales
        return round(mt_rand(0, 50) / 10, 1);
    }
}
