<?php

namespace App\DataFixtures;

use App\Entity\Notifications;
use DateTime;
use DateTimeZone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Services;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Création des services
        $services = [
            'Pebble',
            'KN',
            'RH',
            'Salaire',
        ];

        $serviceEntities = [];
        foreach ($services as $serviceName) {
            $service = new Services();
            $service->setName($serviceName);
            $manager->persist($service);
            $serviceEntities[] = $service;
        }

        // Création des notifications
        for ($i = 0; $i < 100; $i++) {
            $notification = new Notifications();
            $notification->setUserId(rand(1, 10));

            // Sélection d'un service aléatoire
            $randomService = $serviceEntities[array_rand($serviceEntities)];
            $notification->setServices($randomService);

            $notification->setTitle('Notification ' . $i);
            $notification->setMessage('tests des notifications');

            $startDate = new DateTime('now', new DateTimeZone('UTC'));
            $endDate = new DateTime('-1 year', new DateTimeZone('UTC'));
            $timestamp = rand($endDate->getTimestamp(), $startDate->getTimestamp());
            $dateTime = new DateTime('@' . $timestamp);
            $notification->setTimestamp($dateTime);
            $notification->setStatus(rand(1, 4));
            $notification->setUrl('khroniria.fr');

            $manager->persist($notification);
        }

        $manager->flush();
    }
}
