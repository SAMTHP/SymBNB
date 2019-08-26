<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Booking;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {  
        //Initialization of Faker :
        $faker = Factory::create('fr-FR');

        // Instanciation of class Role
        $adminRole = new Role();

        // Set of admin role
        $adminRole->setTitle('ROLE_ADMIN');

        // We persist this role in to database
        $manager->persist($adminRole);

        // Instanciation of user class which will be the futur administrator
        $adminUser = new User();

        // Settings of the user admin
        $adminUser->setFistName('Samir')
                  ->setLastName('Founou')
                  ->setEmail('samir_615@live.fr')
                  ->setHash($this->encoder->encodePassword($adminUser,'623598741'))
                  ->setPicture('https://lh4.googleusercontent.com/-_nBP_FLtER8/AAAAAAAAAAI/AAAAAAAAADE/60chXnGyn0Y/photo.jpg')
                  ->setIntroduction($faker->sentence())
                  ->setDescription('<p>' . join('</p><p>',$faker->paragraphs(3)) . '</p>')
                  ->addUserRole($adminRole);

        // We persist the new user admin
        $manager->persist($adminUser);

        // we generate users 
        $users = [];
        $genres = ['male','female'];

        for($e = 1; $e <= 10; $e++){
            // Firstly we doing an instanciation of Ad class
            $user = new User();

            // Random on gender array
            $genre = $faker->randomElement($genres);

            // Random in order to have different pictureId
            $pictureId = $faker->numberBetween(1,99) . '.jpg';

            // Url from randomuser API
            $picture = "https://randomuser.me/api/portraits/";

            // Set of user avatar
            $picture .= ($genre == 'male' ? 'men/' : 'women/').$pictureId;

            // Hashing of user password
            $hash = $this->encoder->encodePassword($user,'password');

            // Set of user properties with faker methods            
            $firstname = $faker->firstName($genre);
            $lastname = $faker->lastName;
            $email = $faker->email;
            $introduction = $faker->sentence();
            $description = '<p>' . join('</p><p>',$faker->paragraphs(3)) . '</p>';

            // Secondly Configuration of properties of the Ad class
            $user->setFistName($firstname)
                 ->setLastName($lastname)
                 ->setPicture($picture)
                 ->setEmail($email)
                 ->setHash($hash)
                 ->setIntroduction($introduction)
                 ->setDescription($description);

            // To finish, we using the doctrine manager, in order to persist in to database
            $manager->persist($user);
            $users[] = $user;
        }

        // We generate ads
        for($i = 1; $i <= 30; $i++ ){

            // Firstly we doing an instanciation of Ad class
            $ad = new Ad();

            // Set of properties with faker methods
            $title = $faker->sentence();
            $cover_image = $faker->imageUrl(1000,350);
            $introduction = $faker->paragraph(2);
            $content = '<p>' . join('</p><p>',$faker->paragraphs(5)) . '</p>';

            $user = $users[mt_rand(0,(count($users)-1))];

            // Secondly Configuration of properties of the Ad class
            $ad->setTitle($faker->sentence())
                ->setCoverImage($faker->imageUrl())
                ->setIntroduction($faker->paragraph(2))
                ->setContent('<p>' . join('</p><p>',$faker->paragraphs(5)) . '</p>')
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1, 5))
                ->setAuthor($user);

            // We generate images
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

            // Booking admins
            for($g = 1; $g <= mt_rand(0, 10); $g++){
                // Instanciation af Booking class
                $booking = new Booking();

                // We define the created date
                $createdAt = $faker->dateTimeBetween('-6 months');
                // We define the start date
                $startDate = $faker->dateTimeBetween('-3 months');
                // We difine the duration
                $duration = mt_rand(3, 10);
                // We calculate the end date
                $endDate = (clone $startDate)->modify("+$duration days");
                // We calculate the amount of booking
                $amount = $duration * $ad->getPrice();

                // We define the booker
                $booker = $users[mt_rand(0, count($users)-1 )];
                // We add comments
                $comment = $faker->paragraph();

                // We set the booking informations
                $booking->setBooker($booker)
                        ->setAd($ad)
                        ->setStartDate($startDate)
                        ->setEndDate($endDate)
                        ->setCreatedAt($createdAt)
                        ->setAmount($amount)
                        ->setComment($comment);

                // We using the doctrine manager in order to persist this booking in to database
                $manager->persist($booking);

                // Comments admin
                if(mt_rand(0,1)){
                    $comment = new Comment();

                    $comment->setContent($faker->paragraph())
                            ->setRating(mt_rand(1,5))
                            ->setAuthor($booker)
                            ->setAd($ad);
                    
                    $manager->persist($comment);
                }
            }
            // To finish, we using the doctrine manager, in order to persist in to database
            $manager->persist($ad);
        }
        $manager->flush();
    }
}
