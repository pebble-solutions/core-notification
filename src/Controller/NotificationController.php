<?php

namespace App\Controller;

use App\Entity\Notifications;
use App\Repository\NotificationsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class NotificationController extends AbstractController
{


    // Recup de toutes les données
    #[Route('/api/notifications', name: 'notification', methods: ['GET'])]
    public function getNotificationList(NotificationsRepository $notificationsRepository, SerializerInterface $serializer): JsonResponse
    {

        $notificationList = $notificationsRepository->findAll();
        $jsonNotificationList = $serializer->serialize($notificationList, 'json');

        return new JsonResponse($jsonNotificationList, Response::HTTP_OK, [], true);
    }


    // Recup des notifs par ID
    #[Route('/api/notifications/{id}', name: 'detailNotification', methods: ['GET'])]
    public function getDetailNotification(Notifications $notifications , SerializerInterface $serializer): JsonResponse
    {
        $jsonNotification = $serializer->serialize($notifications, 'json');
        return new JsonResponse($jsonNotification, Response::HTTP_OK, ['accept' => 'json'], true);
    }



    // Recup des notif par User_ID
    #[Route('/api/notifications/user/{userId}', name: 'notificationsByUser', methods: ['GET'])]
    public function getNotificationsByUser($userId, NotificationsRepository $notificationsRepository, SerializerInterface $serializer): JsonResponse
    {
        $notifications = $notificationsRepository->findBy(['user_id' => $userId]);
        $jsonNotifications = $serializer->serialize($notifications, 'json');

        return new JsonResponse($jsonNotifications, Response::HTTP_OK, ['Content-Type' => 'application/json'], true);
    }


}

