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

      $faker = Factory::create();

        // Generate categories
        $categories = [];
        for ($i = 0; $i < 5; $i++) {
            $category = new Category();
            $category->setTitle($faker->word);
            $category->setCreatedAt($faker->dateTimeBetween('-2 years', 'now'));
            $manager->persist($category);
            $categories[] = $category;
        }

        // Generate actors
        $actors = [];
        for ($i = 0; $i < 10; $i++) {
            $actor = new Actor();
            $actor->setLastname($faker->lastName);
            $actor->setFirstname($faker->firstName);
            $actor->setDob($faker->dateTimeBetween('-70 years', '-20 years'));
            $actor->setMedia($faker->imageUrl(200, 300, 'people'));
            $actor->setNationality($faker->country);
            $actor->setGender($faker->randomElement(['M', 'F']));
            $actor->setAwards($faker->numberBetween(0, 100));
            $actor->setCreatedAt($faker->dateTimeBetween('-2 years', 'now'));
            $manager->persist($actor);
            $actors[] = $actor;
        }

        // Generate movies and associate actors and categories
        for ($i = 0; $i < 10; $i++) {
            $movie = new Movie();
            $movie->setTitle($faker->sentence(3));
            $movie->setReleaseDate($faker->dateTimeBetween('-30 years', 'now'));
            $movie->setDirector($faker->name);
            $movie->setDescription($faker->paragraph);
            $movie->setMedia($faker->imageUrl(400, 600, 'movies'));
            $movie->setEntries($faker->numberBetween(100000, 1000000));
            $movie->setCreatedAt($faker->dateTimeBetween('-2 years', 'now'));

            // Associate random actors
            for ($j = 0; $j < mt_rand(1, 3); $j++) {
                $movie->addActor($actors[array_rand($actors)]);
            }

            // Associate random categories
            for ($j = 0; $j < mt_rand(1, 2); $j++) {
                $movie->addCategory($categories[array_rand($categories)]);
            }

            $manager->persist($movie);
        }

        // Flush the changes to the database
        $manager->flush();
    }
}
