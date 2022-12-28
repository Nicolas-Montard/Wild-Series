<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public const NBPROGRAM = 8;
    const PROGRAM = [
        ['Walking dead', 'Des zombies envahissent la terre', 'category_Drame'],
        ['Stargate SG-1', 'Le docteur Daniel Jackson est rejeté par la communauté des égyptologues en raison de ses théories controversées sur la fonction des pyramides d\'Égypte qui seraient des lieux d\'atterrissage de vaisseaux spatiaux. Cependant, à la sortie d\'une conférence, il est recruté par Catherine Langford pour travailler sur un projet secret de l\'armée américaine. Arrivé dans la base de Creek Mountain, il découvre une dalle mise au jour en 1928 à Gizeh (Égypte) puis en traduit les écritures du cercle intérieur.', 'category_Science-fiction'],
        ['arcane', 'Championnes de leurs villes jumelles et rivales (la huppée Piltover et la sous-terraine Zaun), deux sœurs Vi et Powder se battent dans une guerre où font rage des technologies magiques et des perspectives diamétralement opposées.', 'category_Animation'],
        ['TheBoys', 'Dans un monde fictif où les super-héros se sont laissés corrompre par la célébrité et la gloire et ont peu à peu révélé la part sombre de leur personnalité, une équipe de justiciers qui se fait appeler "The Boys" décide de passer à l\'action et d\'abattre ces super-héros autrefois appréciés de tous.', 'category_Action'],
        ['Le seigneur des anneaux : les anneaux de pouvoir', 'En passant par les profondeurs des Monts Brumeux et le royaume de Númenor, les héros affrontent la réapparition tant redoutée du mal en Terre du Milieu et créent des héritages qui vivront longtemps après qu\'ils soient partis.', 'category_Fantastique'],
        ['Game of Thrones', 'Après un été de dix années, un hiver rigoureux s\'abat sur le Royaume avec la promesse d\'un avenir des plus sombres. Pendant ce temps, complots et rivalités se jouent sur le continent pour s\'emparer du Trône de Fer, le symbole du pouvoir absolu.', 'category_Fantastique'],
        ['House of the Dragon', 'La série débute 189 ans avant l\'époque de Game of Thrones et raconte les évènements qui ont provoqué une guerre civile appelée La Danse des Dragons (Dance of the Dragons), qui conduit à la perte des dragons et au début de la chute de la Maison Targaryen.', 'category_Fantastique'],
        ['The Witcher', 'Inspiré d\'une série littéraire fantastique à succès, The Witcher est un récit épique sur la famille et le destin. Geralt de Riv, un chasseur de monstres solitaire, se bat pour trouver sa place dans un monde où les humains sont souvent plus vicieux que les bêtes.', 'category_Fantastique']
    ];

    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager)
    {
        $i = 0;
        foreach(self::PROGRAM as $programContent)
        {
            $program = new Program();
            $program->setTitle($programContent[0]);
            $program->setSynopsis($programContent[1]);
            $program->setCategory($this->getReference($programContent[2]));
            $program->setSlug($this->slugger->slug($program->getTitle()));
            $program->setOwner($this->getReference('user_' . rand(0, 9)));
            $manager->persist($program);
            $this->addReference('program_' . $i, $program);
            $i+=1;
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
          CategoryFixtures::class,
          UserFixtures::class,
        ];
    }


}
