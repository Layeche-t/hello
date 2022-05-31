<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Booking;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('FR-fr');


        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser->setFirstName('Lior')
            ->setLastName('Chamla')
            ->setEmail('liro@symfony.com')
            ->setHash($this->encoder->encodePassword($adminUser, 'password'))
            ->setPircture('https://randomuser.me/api/portraits/women/55.jpg')
            ->setIntroduction($faker->sentence())
            ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
            ->addUserRole($adminRole);

        $manager->persist($adminUser);


        $users = [];
        $genres = ['male', 'female'];

        // for entity user 
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();


            $genre = $faker->randomElement($genres);

            $pircture = 'https://randomuser.me/api/portraits/';
            $pirctureId = $faker->numberBetween(1, 99) . '.jpg';

            $pircture .= ($genre == 'male' ? 'men/' : 'women/') . $pirctureId;

            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstname($genre))
                ->setLastName($faker->lastname($genre))
                ->setEmail($faker->email)
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>')
                ->setHash($hash)
                ->setPircture($pircture);


            $manager->persist($user);
            $users[] = $user;
        }

        // for entity Ad
        for ($i = 1; $i <= 30; $i++) {
            $ad = new Ad();

            $title = $faker->sentence();
            $coverImage = "https://picsum.photos/1200/350?random=" . mt_rand(1, 5000);;
            $introduction = $faker->paragraph(2);
            $contents = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';

            $user = $users[mt_rand(0, count($users) - 1)];

            $ad->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContents($contents)
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1, 6))
                ->setAuthor($user);

            // for entity image
            for ($j = 1; $j <= mt_rand(2, 5); $j++) {
                $image = new Image();
                $image->setUrl($coverImage)
                    ->setCaption($faker->sentence())
                    ->setAd($ad);

                $manager->persist($image);
            }

            // for booking
            for ($h = 0; $h <= mt_rand(0, 10); $h++) {

                $booking = new Booking();

                $createdAt = $faker->dateTimeBetween('-6 months');
                $startDate = $faker->dateTimeBetween('-3 months');

                $duration = mt_rand(3, 10);
                $endDate = (clone $startDate)->modify("+$duration days");
                $amount = $ad->getPrice() * $duration;
                $booker = $users[mt_rand(0, count($users) - 1)];
                $comment = $faker->paragraph();

                $booking->setBooker($booker)
                    ->setAd($ad)
                    ->setStartDate($startDate)
                    ->setEndDate($endDate)
                    ->setCreatedAt($createdAt)
                    ->setAmount($amount)
                    ->setComment($comment);

                $manager->persist($booking);
            }

            $manager->persist($ad);
        }

        $manager->flush();
    }
}
