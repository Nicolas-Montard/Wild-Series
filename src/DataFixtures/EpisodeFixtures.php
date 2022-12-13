<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use ContainerGZ5Tmru\getSluggerService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public const NBEPISODE = 10;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    public function load(ObjectManager $manager,): void
    {
        //Puis ici nous demandons à la Factory de nous fournir un Faker
        $faker = Factory::create();

        /**
        * L'objet $faker que tu récupère est l'outil qui va te permettre 
        * de te générer toutes les données que tu souhaites
        */

        for($index = 0; $index < ProgramFixtures::NBPROGRAM; $index++){

            for($i = 0; $i < SeasonFixtures::NBSEASON; $i++) {

                for($e = 0; $e < self::NBEPISODE; $e++){
                    $episode = new Episode();
                    //Ce Faker va nous permettre d'alimenter l'instance de Season que l'on souhaite ajouter en base
                    $episode->setNumber($e+1);
                    $episode->setTitle($faker->words(3, true));
                    $episode->setSynopsis($faker->paragraphs(3, true));
                    $episode->setDuration($faker->numberBetween(40, 80));
                    $episode->setSlug($this->slugger->slug($episode->getTitle()));
                    
                    $episode->setSeason($this->getReference('program_' . $index . 'season_' . $i));
                    
                    $manager->persist($episode);
                }
            }
        }
            $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
          SeasonFixtures::class,
        ];
    }
}
