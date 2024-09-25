<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Movie;
use App\Entity\Actor;
use App\Entity\Category;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $actor = new Actor();
        $actor->setLastname('HANKS');
        $actor->setFirstname('Tom');
        $actor->setDob(new \DateTime('1956-07-09'));
        $actor->setMedia('https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcSKwwFiYctP1goBeaz7lGAhUWFmNc2ghSGNe57OBRZ5jO9ZUbCs');
        $actor->setNationality('Américain');
        $actor->setGender('M');
        $actor->setAwards('83');


        $movie = new Movie();
        $movie->setTitle('Forrest Gump');
        $movie->setReleaseDate(new \DateTime('1994-07-06'));
        $movie->setDirector('Robert ZEMECKIS');
        $movie->setDescription("Sur un banc, à Savannah, en Géorgie, Forrest Gump attend le bus. Comme celui-ci tarde à venir, le jeune homme raconte sa vie à ses compagnons d'ennui. A priori, ses capacités intellectuelles plutôt limitées ne le destinaient pas à de grandes choses. Qu'importe.");
        $movie->setMedia('https://fr.web.img4.acsta.net/pictures/15/10/13/15/12/514297.jpg');
        $movie->setEntries(1000000);
        $movie->setRating(4.3);
        $movie->setDuration(142);

        $category = new Category();
        $category->setTitle('Fantastique');

        $movie->addCategory($category);
        $movie->addActor($actor);
        
        $manager->persist($actor);
        $manager->persist($movie);
        $manager->persist($category);
        $manager->flush();
    }
}
