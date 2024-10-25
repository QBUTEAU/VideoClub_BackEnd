<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\Category;
use App\Entity\Movie;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Xylis\FakerCinema\Provider\Movie as MovieProvider;
use Xylis\FakerCinema\Provider\Person as PersonProvider;

class AppFixtures extends Fixture
{
    private const IMAGE_BASE_URL = 'https://image.tmdb.org/t/p/original';

    public function load(ObjectManager $manager): void
    {
        // Initialisation de Faker avec les providers cinéma
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new MovieProvider($faker));
        $faker->addProvider(new PersonProvider($faker));

        // Générer des catégories uniques
        $categories = [];
        for ($i = 0; $i < 10; $i++) {
            $category = new Category();
            $category->setTitle($faker->movieGenre());
            $manager->persist($category);
            $categories[] = $category;
        }

        $filePath = __DIR__ . '/../../config/image_links.json';
        $imageLinks = json_decode(file_get_contents($filePath), true);
        shuffle($imageLinks); // Mélanger les liens d'images

        $actorFilePath = __DIR__ . '/../../config/person_links.json';
        $actorImage = json_decode(file_get_contents($actorFilePath), true);
        shuffle($actorImage); // Mélanger les liens d'images des acteurs

        // Générer des acteurs
        $actors = [];
        for ($i = 0; $i < 75; $i++) {
            $actor = new Actor();
            $actor->setLastname($faker->lastName())
                  ->setFirstname($faker->firstName())
                  ->setDob($faker->dateTimeBetween('-70 years', '-25 years'))
                  ->setNationality($faker->country())
                  ->setBio($faker->text(200))
                  ->setGender($faker->randomElement(['M', 'F']))
                  ->setAwards($faker->numberBetween(0, 10));

            // Ajouter deathDate de manière aléatoire
            if (mt_rand(0, 1) === 1) {
                $actor->setDeathDate($faker->dateTimeBetween('-15 years', 'now'));
            }

            if (!empty($actorImage)) {
                $randomActorImageLink = array_shift($actorImage); // Prendre et retirer l'image
                $actor->setMedia($randomActorImageLink);
            }

            $manager->persist($actor);
            $actors[] = $actor;
        }

        
        // Générer des films
        for ($i = 0; $i < 50; $i++) {
            $movie = new Movie();
            $movie->setTitle($faker->movie())
                  ->setReleaseDate($faker->dateTimeBetween('-30 years', 'now'))
                  ->setDirector($faker->director())
                  ->setDescription($faker->overview())
                  ->setDuration($faker->numberBetween(90, 195))
                  ->setEntries($faker->numberBetween(100000, 1000000))
                  ->setRating($this->generateRandomRating());

            if (!empty($imageLinks)) {
                $randomImageLink = array_shift($imageLinks);
                $movie->setMedia(self::IMAGE_BASE_URL . $randomImageLink);
            }

            // Associer des acteurs aléatoires
            for ($j = 0; $j < 3; $j++) {
                $movie->addActor($actors[array_rand($actors)]);
            }

            // Associer des catégories aléatoires
            for ($j = 0; $j < mt_rand(1, 2); $j++) {
                $movie->addCategory($categories[array_rand($categories)]);
            }

            $manager->persist($movie);
        }

        $user = new User();
        $user->setEmail('qbuteau@mmi.fr')
             ->setPassword(password_hash('WR505D', PASSWORD_BCRYPT));

        $user->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);

        $manager->flush();
    }

    private function generateRandomRating(): float
    {
        return mt_rand(10, 50) / 10;
    }
}