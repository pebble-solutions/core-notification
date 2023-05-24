<?php

namespace App\Controller;

use App\Entity\Notifications;
use App\Repository\NotificationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class ApiNotificationController extends AbstractController
{


    // Recup de toutes les données
    #[Route('/api/notifications', name: 'notification', methods: ['GET'])]
    public function getNotificationList(NotificationsRepository $notificationsRepository, SerializerInterface $serializer): JsonResponse
    {

        $notificationList = $notificationsRepository->findAll();
        $jsonNotificationList = $serializer->serialize($notificationList, 'json');

        return new JsonResponse($jsonNotificationList, Response::HTTP_OK, [], true);
    }
    
    // Nouvelle notif
    #[Route('/api/notifications', name:"createNotification", methods: ['POST'])]
    public function createNotification(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator): JsonResponse
    {

        $notif = $serializer->deserialize($request->getContent(), Notifications::class, 'json');
        $em->persist($notif);
        $em->flush();

        $jsonNotif = $serializer->serialize($notif, 'json', ['Groups' => 'getNotification']);

        $location = $urlGenerator->generate('detailNotification', ['id' => $notif->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonNotif, Response::HTTP_CREATED, ["Location" => $location], true);
    }


    // Modifier une notifications
    #[Route('/api/notifications/{id}', name:"updateBook", methods:['PUT'])]

    public function updateNotification(Request $request, SerializerInterface $serializer, Notifications $currentNotif, EntityManagerInterface $em ): JsonResponse
    {
        $updatedNotif = $serializer->deserialize($request->getContent(), Notifications::class,'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $currentNotif]);

        $em->persist($updatedNotif);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    // Supprimer une notification
    #[Route('/api/notifications/{id}', name: 'deleteNotification', methods: ['DELETE'])]
    public function deleteNotification(Notifications $notifications, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($notifications);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }


    // Recup des notifs par ID
    #[Route('/api/notifications/{id}', name: 'detailNotification', methods: ['GET'])]
    public function getDetailNotification(Notifications $notifications , SerializerInterface $serializer): JsonResponse
    {
        $jsonNotification = $serializer->serialize($notifications, 'json');
        return new JsonResponse($jsonNotification, json:true);
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

