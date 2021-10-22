<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Board;
use DateTimeInterface;
use App\Entity\Comment;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Validator\Constraints\Date;

class BoardFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        for($i = 1; $i <= 6 ; $i++){
            $category = new Category();
            $category ->setTitle($faker->sentence());

            $manager->persist($category);

            for($j = 1; $j <= mt_rand(2, 5); $j++) {
                $board = new Board();

               

                $board->setTitle($faker->sentence())
                      ->setAuthor($faker->firstName())
                      ->setDescription($faker->paragraph())
                      ->setImage("Image de la table n°$i")
                      ->setPlayers(["joueur1" , "joueur2" , "joueur3"  , "joueur4"])
                      ->setGame($faker->sentence($nbWords = 3, $variableNbWords = true))
                      ->setDate($faker->dateTimeBetween($startDate = 'now', $endDate = '2025-01-01', $timezone = null))
                      ->setCategory($category)
                      ->setFull($faker->boolean($chanceOfGettingTrue = 50))
                      ->setOnline($faker->boolean($chanceOfGettingTrue = 50));
    
                $manager->persist($board);

                for($k=1; $k <= mt_rand(4, 7); $k++){
                    $comment = new Comment(); 
                   

                    $comment->setAuthor($faker->name())
                            ->setContent($faker->paragraph())
                            ->setCreatedAt($faker->dateTimeBetween('-2 weeks'))
                            ->setBoard($board);

                            $manager->persist($comment);
                }
    
            }
        }

        

        $manager->flush();
    }
}
