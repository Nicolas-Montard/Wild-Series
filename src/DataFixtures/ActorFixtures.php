<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i=0; $i <10 ; $i++) {
            $actor = new Actor();
            $actor->setName($faker->name());
            $programNumber = [];
            for ($o=0; $o <ProgramFixtures::NBPROGRAM ; $o++) { 
                $programNumber[] = $o;
            }
            $programSelectors = array_rand($programNumber, 3);
            foreach ($programSelectors as $programSelector) {
                $actor->addProgram($this->getReference('program_' . $programSelector));
            }
            $manager->persist($actor);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            ProgramFixtures::class,
        ];
    }
}
