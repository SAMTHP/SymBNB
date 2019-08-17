<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {  
        //Initialization of Faker :
        $faker = Factory::create('fr-FR');

        // Set of properties with faker methods
        $title = $faker->sentence();
        $cover_image = $faker->imageUrl(1000,350);
        $introduction = $faker->paragraph(2);
        $content = '<p>' . join('</p><p>',$faker->paragraphs(5)) . '</p>';

        for($i = 1; $i <= 30; $i++ ){
            // Firstly we doing an instanciation of Ad class
            $ad = new Ad();

            // Secondly Configuration of properties of the Ad class
            $ad->setTitle($faker->sentence())
                ->setCoverImage($faker->imageUrl())
                ->setIntroduction($faker->paragraph(2))
                ->setContent('<p>' . join('</p><p>',$faker->paragraphs(5)) . '</p>')
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1, 5));

            for($j = 1; $j <= mt_rand(2, 5) ; $j++){
                // Firstly we doing an instanciation of Image class
                $image = new Image();

                // Secondly Configuration of properties of the Image class
                $image->setCaption($faker->sentence())
                      ->setUrl($faker->imageUrl())
                      ->setAd($ad);
                
                // To finish, we using the doctrine manager, in order to persist in to database
                $manager->persist($image);

            }
            
            // To finish, we using the doctrine manager, in order to persist in to database
            $manager->persist($ad);
        }

        $manager->flush();
    }
}
