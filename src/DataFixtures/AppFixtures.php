<?php

namespace App\DataFixtures;

use App\Entity\Notifications;
use DateTime;
use DateTimeZone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Notifier\Notification\Notification;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Création d'une vingtaine de livres ayant pour titre
        for ($i = 0; $i < 20; $i++) {
            $notifications = new Notifications();
            $notifications->setuserId(rand(1, 10));
            $notifications->setServicesId(rand(1, 10));
            $notifications->settitle('Notification ' . $i);
            $notifications->setmessage('tests des notifications');


            $startDate = new DateTime('now', new DateTimeZone('UTC'));
            $endDate = new DateTime('-1 year', new DateTimeZone('UTC'));
            $timestamp = rand($endDate->getTimestamp(), $startDate->getTimestamp());
            $dateTime = new DateTime('@' . $timestamp); // Crée un objet DateTime à partir du timestamp
            $notifications->setTimestamp($dateTime); // Utilise l'objet DateTime comme argument
            $notifications->setstatus(rand(1, 4));
            $notifications->seturl('khroniria.fr');
            $manager->persist($notifications);
        }

        $manager->flush();
    }
}
